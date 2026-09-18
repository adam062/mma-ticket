<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Booking $booking): bool
    {
        return $user->isAdmin();
    }

    public function approve(User $user): bool
    {
        return $user->isAdmin();
    }

    public function deny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function requestResubmission(User $user): bool
    {
        return $user->isAdmin();
    }
}
