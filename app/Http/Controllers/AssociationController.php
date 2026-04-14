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
                    $associations[] = [
                        'name'       => $name,
                        'size'       => $this->dirSize($dir),
                        'modified'   => $stat['mtime'] ?? null,
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
}
