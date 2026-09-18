<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    const STATUS_ACTIVE = 'active';
    const STATUS_USED = 'used';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'booking_id',
        'ticket_type_id',
        'serial',
        'qr_token',
        'status',
        'used_at',
        'used_by',
    ];

    protected $casts = [
        'used_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(TicketType::class);
    }

    public function usedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'used_by');
    }

    public function deliveries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TicketDelivery::class);
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => __('Active'),
            self::STATUS_USED => __('Used'),
            self::STATUS_CANCELLED => __('Cancelled'),
            default => __('Unknown'),
        };
    }

    public function getStatusColorClass(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'bg-green-100 text-green-800',
            self::STATUS_USED => 'bg-gray-100 text-gray-800',
            self::STATUS_CANCELLED => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
