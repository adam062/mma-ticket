<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketDelivery extends Model
{
    const STATUS_SENT = 'sent';
    const STATUS_FAILED = 'failed';

    protected $fillable = [
        'ticket_id',
        'channel',
        'status',
        'sent_at',
        'error_message',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public static function record(string $ticketId, string $channel, string $status, ?string $error = null): void
    {
        self::create([
            'ticket_id' => $ticketId,
            'channel' => $channel,
            'status' => $status,
            'sent_at' => now(),
            'error_message' => $error,
        ]);
    }
}
