<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'status',
        'is_super_admin',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password'       => 'hashed',
            'is_super_admin' => 'boolean',
            'last_login_at'  => 'datetime',
            'deleted_at'     => 'datetime',
        ];
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions', 'user_id', 'permission_id')
                    ->withPivot('granted_by')
                    ->withTimestamps();
    }

    public function logs()
    {
        return $this->hasMany(ActionLog::class);
    }

    public function gravatar(int $size = 80): string
    {
        $hash = md5(strtolower(trim($this->email)));
        $initials = urlencode($this->initials());
        $fallback = urlencode("https://ui-avatars.com/api/{$initials}/{$size}/8a4dfd/ffffff/2/0.4/true");

        return "https://www.gravatar.com/avatar/{$hash}?s={$size}&d={$fallback}";
    }

    public function initials(): string
    {
        $parts = explode(' ', trim($this->name));

        return strtoupper(mb_substr($parts[0] ?? '', 0, 1) . mb_substr(end($parts), 0, 1));
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isSuperAdmin(): bool
    {
        return (bool) $this->is_super_admin;
    }

    public function hasPermission(string $key): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->permissions()->where('key', $key)->exists();
    }
}
