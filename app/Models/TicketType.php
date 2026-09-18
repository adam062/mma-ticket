<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    protected $fillable = [
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'price',
        'capacity',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'integer',
        'capacity' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getNameAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    public function getDescriptionAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->description_ar : $this->description_en;
    }

    public function bookings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function tickets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function getRemainingCapacity(): int
    {
        return max(0, $this->capacity - $this->tickets()->count());
    }
}
