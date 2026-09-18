<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_DENIED = 'denied';
    const STATUS_RESUBMISSION_REQUIRED = 'resubmission_required';

    protected $fillable = [
        'reference',
        'name',
        'email',
        'phone',
        'transfer_phone',
        'ticket_type_id',
        'quantity',
        'unit_price',
        'total_amount',
        'payment_method',
        'status',
        'approved_by',
        'approved_at',
        'denial_reason',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'integer',
        'total_amount' => 'integer',
        'approved_at' => 'datetime',
    ];

    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(TicketType::class);
    }

    public function paymentProofs(): HasMany
    {
        return $this->hasMany(PaymentProof::class);
    }

    public function latestProof(): BelongsTo
    {
        return $this->belongsTo(PaymentProof::class)->ofMany('created_at', 'max');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function adminReviews(): HasMany
    {
        return $this->hasMany(AdminReview::class);
    }

    public function telegramLink(): HasMany
    {
        return $this->hasMany(TelegramLink::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => __('Pending Payment'),
            self::STATUS_APPROVED => __('Approved'),
            self::STATUS_DENIED => __('Denied'),
            self::STATUS_RESUBMISSION_REQUIRED => __('Resubmission Required'),
            default => __('Unknown'),
        };
    }

    public function getStatusColorClass(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_APPROVED => 'bg-green-100 text-green-800',
            self::STATUS_DENIED => 'bg-red-100 text-red-800',
            self::STATUS_RESUBMISSION_REQUIRED => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function remainingTickets(): int
    {
        return $this->quantity - $this->tickets()->count();
    }
}
