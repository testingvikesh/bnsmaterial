<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_STAFF = 'staff';

    public const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_STAFF,
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'status',
        'is_guest',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_guest' => 'boolean',
    ];

    public function isGuest(): bool
    {
        return filter_var($this->is_guest, FILTER_VALIDATE_BOOLEAN);
    }

    public function typeLabel(): string
    {
        return $this->isGuest() ? 'Guest' : 'Member';
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isStaff(): bool
    {
        return $this->role === self::ROLE_STAFF;
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_STAFF => 'Staff',
            default => ucfirst((string) $this->role),
        };
    }

    public function homeRoute(): string
    {
        return route('dashboard');
    }

    public function memberProfile(): HasOne
    {
        return $this->hasOne(MemberProfile::class);
    }

    public function mobileNumber(): string
    {
        $phone = trim((string) $this->phone);
        if ($phone !== '') {
            return $phone;
        }

        $whatsapp = trim((string) ($this->memberProfile?->whatsapp ?? ''));

        return $whatsapp !== '' ? $whatsapp : '—';
    }
}
