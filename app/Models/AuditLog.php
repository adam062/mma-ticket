<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $fillable = [
        'admin_id',
        'action',
        'related_type',
        'related_id',
        'note',
        'ip_address',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public static function log(string $action, mixed $related = null, ?string $note = null, ?User $admin = null): void
    {
        self::create([
            'admin_id' => $admin?->id ?? auth()->id(),
            'action' => $action,
            'related_type' => $related ? get_class($related) : null,
            'related_id' => $related ? $related->id : null,
            'note' => $note,
            'ip_address' => request()->ip(),
        ]);
    }
}
