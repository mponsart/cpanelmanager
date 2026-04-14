<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function __construct(private LoggerService $logger) {}

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Redirection vers Google OAuth.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Callback Google OAuth — connexion exclusive.
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            $this->logger->auth('login', 'error', 'Échec OAuth Google : ' . $e->getMessage(), $request);

            return redirect()->route('login')->with('error', 'Échec de l\'authentification Google. Veuillez réessayer.');
        }

        // Recherche par google_id OU par e-mail (premier lien)
        $user = User::where('google_id', $googleUser->getId())
                    ->orWhere('email', $googleUser->getEmail())
                    ->first();

        if (! $user) {
            $this->logger->auth('login', 'error', 'Aucun compte associé : ' . $googleUser->getEmail(), $request);

            return redirect()->route('login')->with('error', 'Aucun compte technicien associé à cette adresse Google. Contactez un administrateur.');
        }

        if (! $user->isActive()) {
            $this->logger->auth('login', 'error', 'Compte désactivé : ' . $googleUser->getEmail(), $request);

            return redirect()->route('login')->with('error', 'Votre compte est désactivé. Contactez un administrateur.');
        }

        // Lier google_id si premier login Google
        if (! $user->google_id) {
            $user->google_id = $googleUser->getId();
        }

        $user->last_login_at = now();
        $user->last_login_ip = $request->ip();
        $user->save();

        Auth::login($user);
        $request->session()->regenerate();

        $this->logger->auth('login', 'success', null, $request);

        return redirect()->route('dashboard')->with('success', 'Bienvenue, ' . e($user->name) . '.');
    }

    public function logout(Request $request)
    {
        $this->logger->auth('logout', 'success', null, $request);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Vous avez été déconnecté.');
    }
}
