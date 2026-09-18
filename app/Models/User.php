<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeGateMan($query)
    {
        return $query->where('role', 'gate_man');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGateMan(): bool
    {
        return $this->role === 'gate_man';
    }

    public function adminReviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AdminReview::class, 'admin_id');
    }

    public function auditLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AuditLog::class, 'admin_id');
    }

    public function usedTickets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Ticket::class, 'used_by');
    }
}
