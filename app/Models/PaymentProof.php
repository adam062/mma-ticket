<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentProof extends Model
{
    protected $fillable = [
        'booking_id',
        'payment_method',
        'transfer_phone',
        'amount',
        'screenshot_path',
        'submitted_at',
    ];

    protected $casts = [
        'amount' => 'integer',
        'submitted_at' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
