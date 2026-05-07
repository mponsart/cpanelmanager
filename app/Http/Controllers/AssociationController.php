<?php

namespace App\Http\Controllers;

use App\Services\LoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use RuntimeException;

class AssociationController extends Controller
{
    private string $basePath;

    public function __construct(
        private LoggerService $logger
    ) {
        $this->basePath = rtrim(config('cpanel.monasso_path'), '/');

        if (empty($this->basePath)) {
            throw new RuntimeException('MONASSO_PATH non configuré dans .env');
        }
    }

    public function index(Request $request)
    {
        $associations = [];

        try {
            if (File::isDirectory($this->basePath)) {
                $dirs = File::directories($this->basePath);

                foreach ($dirs as $dir) {
                    $name = basename($dir);
                    $stat = stat($dir);
                    $suspendedFile = $dir . '/.suspended';
                    $suspendedData = null;
                    if (File::exists($suspendedFile)) {
                        $raw = File::get($suspendedFile);
                        $suspendedData = json_decode($raw, true) ?: [];
                    }
                    $associations[] = [
                        'name'         => $name,
                        'size'         => $this->dirSize($dir),
                        'modified'     => $stat['mtime'] ?? null,
                        'quota_gb'     => $this->readStorageQuotaGb($dir),
                        'suspended'    => $suspendedData !== null,
                        'suspend_info' => $suspendedData,
                    ];
                }

                usort($associations, fn($a, $b) => strcasecmp($a['name'], $b['name']));
            }

            $this->logger->success('list_associations', 'association', null, [], $request);
        } catch (\Throwable $e) {
            $this->logger->error('list_associations', 'association', $e->getMessage(), null, [], $request);
            session()->flash('error', e($e->getMessage()));
        }

        return view('association.index', compact('associations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'regex:/^[a-zA-Z0-9_-]+$/', 'max:100'],
        ]);

        $path = $this->basePath . '/' . $data['name'];

        if (File::isDirectory($path)) {
            return back()->withInput()->with('error', 'Ce dossier existe déjà.');
        }

        try {
            File::makeDirectory($path, 0755, true);
            $this->writeStorageQuotaGb($path, 10);

            $this->logger->success('create_association', 'association', $data['name'], $data, $request);

            return redirect()->route('association.index')->with('success', 'Association « ' . e($data['name']) . ' » créée.');
        } catch (\Throwable $e) {
            $this->logger->error('create_association', 'association', $e->getMessage(), null, $data, $request);
            return back()->withInput()->with('error', e($e->getMessage()));
        }
    }

    public function rename(Request $request)
    {
        $data = $request->validate([
            'old_name' => ['required', 'string', 'regex:/^[a-zA-Z0-9_-]+$/', 'max:100'],
            'new_name' => ['required', 'string', 'regex:/^[a-zA-Z0-9_-]+$/', 'max:100'],
        ]);

        $oldPath = $this->basePath . '/' . $data['old_name'];
        $newPath = $this->basePath . '/' . $data['new_name'];

        if (! File::isDirectory($oldPath)) {
            return back()->with('error', 'Le dossier source n\'existe pas.');
        }

        if (File::isDirectory($newPath)) {
            return back()->with('error', 'Un dossier avec ce nom existe déjà.');
        }

        try {
            File::moveDirectory($oldPath, $newPath);

            $this->logger->success('rename_association', 'association', $data['old_name'] . ' → ' . $data['new_name'], $data, $request);

            return redirect()->route('association.index')->with('success', 'Association renommée en « ' . e($data['new_name']) . ' ».');
        } catch (\Throwable $e) {
            $this->logger->error('rename_association', 'association', $e->getMessage(), null, $data, $request);
            return back()->with('error', e($e->getMessage()));
        }
    }

    public function suspend(Request $request)
    {
        $data = $request->validate([
            'name'   => ['required', 'string', 'regex:/^[a-zA-Z0-9_-]+$/', 'max:100'],
            'reason' => ['required', 'string', 'min:5', 'max:500'],
        ]);

        $path = $this->basePath . '/' . $data['name'];

        if (! File::isDirectory($path)) {
            return back()->with('error', 'Le dossier n\'existe pas.');
        }

        if (File::exists($path . '/.suspended')) {
            return back()->with('error', 'Cette association est déjà suspendue.');
        }

        try {
            // Sauvegarder l'éventuel .htaccess existant
            if (File::exists($path . '/.htaccess')) {
                File::move($path . '/.htaccess', $path . '/.htaccess.pre-suspend');
            }

            // Créer le .htaccess de redirection vers la page de suspension
            // On utilise Redirect (mod_alias) plutôt que RewriteRule pour éviter
            // tout effet de bord sur les autres répertoires frères.
            File::put($path . '/.htaccess', implode("\n", [
                '<IfModule mod_alias.c>',
                '    Redirect 302 / https://monasso.eu/errors/suspended-instance',
                '</IfModule>',
                '<IfModule !mod_alias.c>',
                '    RewriteEngine On',
                '    RewriteOptions InheritDownBefore',
                '    RewriteRule ^ https://monasso.eu/errors/suspended-instance [R=302,L,END]',
                '</IfModule>',
            ]));

            // Marqueur de suspension avec métadonnées
            File::put($path . '/.suspended', json_encode([
                'reason'       => $data['reason'],
                'suspended_by' => auth()->user()->name ?? auth()->user()->email,
                'suspended_at' => now('Europe/Paris')->toIso8601String(),
            ], JSON_UNESCAPED_UNICODE));

            $this->logger->success('suspend_association', 'association', $data['name'], $data, $request);

            return redirect()->route('association.index')->with('success', 'Association « ' . e($data['name']) . ' » suspendue.');
        } catch (\Throwable $e) {
            $this->logger->error('suspend_association', 'association', $e->getMessage(), null, $data, $request);
            return back()->with('error', e($e->getMessage()));
        }
    }

    public function setStorageQuota(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'regex:/^[a-zA-Z0-9_-]+$/', 'max:100'],
            'quota_gb' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        $path = $this->basePath . '/' . $data['name'];

        if (! File::isDirectory($path)) {
            return back()->with('error', 'Le dossier n\'existe pas.');
        }

        try {
            $this->writeStorageQuotaGb($path, (int) $data['quota_gb']);

            $this->logger->success('set_association_storage_quota', 'association', $data['name'], $data, $request);

            return redirect()->route('association.index')->with('success', 'Quota de « ' . e($data['name']) . ' » défini à ' . (int) $data['quota_gb'] . ' Go.');
        } catch (\Throwable $e) {
            $this->logger->error('set_association_storage_quota', 'association', $e->getMessage(), null, $data, $request);
            return back()->with('error', e($e->getMessage()));
        }
    }

    public function unsuspend(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'regex:/^[a-zA-Z0-9_-]+$/', 'max:100'],
        ]);

        $path = $this->basePath . '/' . $data['name'];

        if (! File::isDirectory($path)) {
            return back()->with('error', 'Le dossier n\'existe pas.');
        }

        if (! File::exists($path . '/.suspended')) {
            return back()->with('error', 'Cette association n\'est pas suspendue.');
        }

        try {
            // Supprimer le .htaccess de suspension
            File::delete($path . '/.htaccess');

            // Restaurer l'éventuel .htaccess d'origine
            if (File::exists($path . '/.htaccess.pre-suspend')) {
                File::move($path . '/.htaccess.pre-suspend', $path . '/.htaccess');
            }

            // Supprimer le marqueur de suspension
            File::delete($path . '/.suspended');

            $this->logger->success('unsuspend_association', 'association', $data['name'], $data, $request);

            return redirect()->route('association.index')->with('success', 'Association « ' . e($data['name']) . ' » réactivée.');
        } catch (\Throwable $e) {
            $this->logger->error('unsuspend_association', 'association', $e->getMessage(), null, $data, $request);
            return back()->with('error', e($e->getMessage()));
        }
    }

    public function destroy(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'regex:/^[a-zA-Z0-9_-]+$/', 'max:100'],
        ]);

        $path = $this->basePath . '/' . $data['name'];

        if (! File::isDirectory($path)) {
            return back()->with('error', 'Le dossier n\'existe pas.');
        }

        try {
            File::deleteDirectory($path);

            $this->logger->success('delete_association', 'association', $data['name'], $data, $request);

            return redirect()->route('association.index')->with('success', 'Association « ' . e($data['name']) . ' » supprimée.');
        } catch (\Throwable $e) {
            $this->logger->error('delete_association', 'association', $e->getMessage(), null, $data, $request);
            return back()->with('error', e($e->getMessage()));
        }
    }

    /**
     * Calcule la taille d'un répertoire en octets (récursif).
     */
    private function dirSize(string $dir): int
    {
        $size = 0;
        foreach (File::allFiles($dir) as $file) {
            $size += $file->getSize();
        }
        return $size;
    }

    private function readStorageQuotaGb(string $dir): ?int
    {
        $configPath = $dir . '/config.local.php';

        if (! File::exists($configPath)) {
            return null;
        }

        $content = File::get($configPath);

        if (preg_match('/define\(\s*[\'\"]Paheko\\\\?FILE_STORAGE_QUOTA[\'\"]\s*,\s*(\d+)\s*\*\s*1024\s*\*\*\s*3\s*\)\s*;?/i', $content, $matches)) {
            return (int) $matches[1];
        }

        if (preg_match('/define\(\s*[\'\"]Paheko\\\\?FILE_STORAGE_QUOTA[\'\"]\s*,\s*(\d+)\s*\)\s*;?/i', $content, $matches)) {
            $bytes = (int) $matches[1];
            if ($bytes > 0) {
                return (int) floor($bytes / (1024 ** 3));
            }
        }

        return null;
    }

    private function writeStorageQuotaGb(string $dir, int $quotaGb): void
    {
        $configPath = $dir . '/config.local.php';
        $line = "define('Paheko\\\\FILE_STORAGE_QUOTA', {$quotaGb} * 1024 ** 3);";

        if (! File::exists($configPath)) {
            File::put($configPath, "<?php\n\n" . $line . "\n");
            return;
        }

        $content = File::get($configPath);

        if (preg_match('/define\(\s*[\'\"]Paheko\\\\?FILE_STORAGE_QUOTA[\'\"]\s*,.*?\)\s*;?/i', $content)) {
            $content = preg_replace('/define\(\s*[\'\"]Paheko\\\\?FILE_STORAGE_QUOTA[\'\"]\s*,.*?\)\s*;?/i', $line, $content, 1);
        } else {
            $separator = str_ends_with($content, "\n") ? '' : "\n";
            $content .= $separator . $line . "\n";
        }

        File::put($configPath, $content);
    }
}
