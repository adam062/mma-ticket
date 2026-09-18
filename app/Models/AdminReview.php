<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminReview extends Model
{
    protected $fillable = [
        'booking_id',
        'admin_id',
        'action',
        'note',
        'payment_confirmed',
    ];

    protected $casts = [
        'payment_confirmed' => 'boolean',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
