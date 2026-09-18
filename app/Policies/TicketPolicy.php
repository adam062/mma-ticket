<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Ticket $ticket): bool
    {
        return $user->isAdmin();
    }

    public function use(User $user): bool
    {
        return $user->isGateMan();
    }

    public function resend(User $user): bool
    {
        return $user->isAdmin();
    }
}
