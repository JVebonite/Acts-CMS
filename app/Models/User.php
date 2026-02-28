<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'profile_photo',
        'is_active',
        'two_factor_secret',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function isAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    public function recordedDonations()
    {
        return $this->hasMany(Donation::class, 'recorded_by');
    }

    public function recordedExpenses()
    {
        return $this->hasMany(Expense::class, 'recorded_by');
    }
}
