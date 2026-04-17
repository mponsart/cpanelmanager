<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AssociationController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\CredentialsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FtpController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\CpanelAccessController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ─── Auth (non protégées) ─────────────────────────────────────────────────────
Route::get('/',      [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin']);
Route::get('/auth/google',          [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── Zone protégée ────────────────────────────────────────────────────────────
Route::middleware(['auth.panel'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Journaux
    Route::get('/logs',          [LogController::class, 'index'])->name('logs.index');
    Route::get('/logs/{log}',    [LogController::class, 'show'])->name('logs.show');

    // ── Utilisateurs ──────────────────────────────────────────────────────────
    Route::middleware('permission:manage_users')->group(function () {
        Route::get('/users',                          [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create',                   [UserController::class, 'create'])->name('users.create');
        Route::post('/users',                         [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}',                   [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit',              [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}',                   [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}',                [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{id}/restore',            [UserController::class, 'restore'])->name('users.restore');
    });

    // ── Permissions ───────────────────────────────────────────────────────────
    Route::middleware('permission:manage_users')->group(function () {
        Route::get('/permissions',                       [PermissionController::class, 'index'])->name('permissions.index');
        Route::get('/permissions/user/{user}',           [PermissionController::class, 'userPermissions'])->name('permissions.user');
        Route::post('/permissions/grant/{user}',         [PermissionController::class, 'grant'])->name('permissions.grant');
        Route::delete('/permissions/{user}/{permission}',[PermissionController::class, 'revoke'])->name('permissions.revoke');
        Route::put('/permissions/sync/{user}',           [PermissionController::class, 'sync'])->name('permissions.sync');
    });

    // ── E-mails ───────────────────────────────────────────────────────────────
    Route::get('/email',            [EmailController::class, 'index'])->name('email.index')->middleware('permission:view_email');
    Route::post('/email',           [EmailController::class, 'store'])->name('email.store')->middleware('permission:create_email');
    Route::delete('/email',         [EmailController::class, 'destroy'])->name('email.destroy')->middleware('permission:delete_email');
    Route::patch('/email/password', [EmailController::class, 'resetPassword'])->name('email.reset-password')->middleware('permission:create_email');
    Route::get('/email/forwarders', [EmailController::class, 'forwarders'])->name('email.forwarders')->middleware('permission:view_email');
    Route::post('/email/forwarders',[EmailController::class, 'addForwarder'])->name('email.add-forwarder')->middleware('permission:create_email');
    Route::delete('/email/forwarders', [EmailController::class, 'deleteForwarder'])->name('email.delete-forwarder')->middleware('permission:delete_email');

    // ── Bases de données ──────────────────────────────────────────────────────
    Route::get('/database',                [DatabaseController::class, 'index'])->name('database.index')->middleware('permission:view_db');
    Route::post('/database/db',            [DatabaseController::class, 'createDatabase'])->name('database.create-db')->middleware('permission:create_db');
    Route::post('/database/user',          [DatabaseController::class, 'createUser'])->name('database.create-user')->middleware('permission:create_db');
    Route::post('/database/privileges',    [DatabaseController::class, 'assignPrivileges'])->name('database.privileges')->middleware('permission:create_db');

    // ── Domaines ──────────────────────────────────────────────────────────────
    Route::get('/domain',              [DomainController::class, 'index'])->name('domain.index')->middleware('permission:view_domain');
    Route::post('/domain/addon',       [DomainController::class, 'createAddon'])->name('domain.create-addon')->middleware('permission:create_domain');
    Route::post('/domain/subdomain',   [DomainController::class, 'createSubdomain'])->name('domain.create-subdomain')->middleware('permission:create_domain');

    // ── FTP ───────────────────────────────────────────────────────────────────
    Route::get('/ftp',         [FtpController::class, 'index'])->name('ftp.index')->middleware('permission:view_ftp');
    Route::post('/ftp',        [FtpController::class, 'store'])->name('ftp.store')->middleware('permission:create_ftp');
    Route::delete('/ftp',      [FtpController::class, 'destroy'])->name('ftp.destroy')->middleware('permission:create_ftp');

    // ── Cron Jobs ─────────────────────────────────────────────────────────────
    Route::get('/cron',        [CronController::class, 'index'])->name('cron.index')->middleware('permission:manage_cron');
    Route::post('/cron',       [CronController::class, 'store'])->name('cron.store')->middleware('permission:manage_cron');
    Route::delete('/cron',     [CronController::class, 'destroy'])->name('cron.destroy')->middleware('permission:manage_cron');

    // ── Statistiques ──────────────────────────────────────────────────────────
    Route::get('/stats',          [StatsController::class, 'index'])->name('stats.index')->middleware('permission:view_stats');
    Route::get('/stats/{domain}', [StatsController::class, 'domainDetail'])->name('stats.domain')->middleware('permission:view_stats')->where('domain', '[a-zA-Z0-9._-]+');

    // ── Associations MonAsso ──────────────────────────────────────────────────
    Route::get('/associations',           [AssociationController::class, 'index'])->name('association.index')->middleware('permission:view_associations');
    Route::post('/associations',          [AssociationController::class, 'store'])->name('association.store')->middleware('permission:manage_associations');
    Route::patch('/associations/rename',  [AssociationController::class, 'rename'])->name('association.rename')->middleware('permission:manage_associations');
    Route::delete('/associations',        [AssociationController::class, 'destroy'])->name('association.destroy')->middleware('permission:manage_associations');

    // ── Accès cPanel ──────────────────────────────────────────────────────────
    Route::get('/cpanel',               [CpanelAccessController::class, 'index'])->name('cpanel.index')->middleware('permission:access_cpanel');
    Route::post('/cpanel/connect',      [CpanelAccessController::class, 'connect'])->name('cpanel.connect')->middleware('permission:access_cpanel');
    Route::post('/cpanel/manual-login', [CpanelAccessController::class, 'manualLogin'])->name('cpanel.manual-login')->middleware('permission:access_cpanel');

    // ── Identifiants confidentiels (super-admin + permission view_credentials) ──
    Route::get('/credentials', [CredentialsController::class, 'index'])->name('credentials.index')->middleware('permission:view_credentials');
});
