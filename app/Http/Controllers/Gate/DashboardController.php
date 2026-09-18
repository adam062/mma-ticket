<?php

namespace App\Http\Controllers\Gate;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => Ticket::count(),
            'active' => Ticket::where('status', 'active')->count(),
            'used' => Ticket::where('status', 'used')->count(),
            'cancelled' => Ticket::where('status', 'cancelled')->count(),
        ];
        return view('gate.dashboard', compact('stats'));
    }

    public function verifyForm()
    {
        return view('gate.verify');
    }

    public function verifyQr(Request $request)
    {
        $request->validate([
            'qr_token' => 'required|string|max:255',
        ]);

        $ticket = Ticket::where('qr_token', $request->qr_token)->first();

        return $this->showResult($ticket, $request->qr_token);
    }

    public function verifySerial(Request $request)
    {
        $request->validate([
            'serial' => 'required|string|max:50',
        ]);

        $ticket = Ticket::where('serial', $request->serial)->first();

        return $this->showResult($ticket, $request->serial);
    }

    public function lookup(string $value)
    {
        $ticket = Ticket::where('serial', $value)->orWhere('qr_token', $value)->first();

        if (! $ticket) {
            return view('gate.result', [
                'status' => 'invalid',
                'message' => __('INVALID TICKET'),
                'message_ar' => 'تذكرة غير صالحة',
                'ticket' => null,
            ]);
        }

        $status = match ($ticket->status) {
            Ticket::STATUS_USED => 'used',
            Ticket::STATUS_CANCELLED => 'cancelled',
            default => 'valid',
        };

        $message = match ($status) {
            'used' => __('ALREADY USED'),
            'cancelled' => __('CANCELLED TICKET'),
            default => __('VALID TICKET'),
        };

        $messageAr = match ($status) {
            'used' => 'التذكرة مستخدمة بالفعل',
            'cancelled' => 'تذكرة ملغاة',
            default => 'تذكرة صالحة',
        };

        return view('gate.result', compact('status', 'message', 'messageAr', 'ticket'));
    }

    public function markUsed(Request $request, Ticket $ticket)
    {
        $adminId = $request->user()->id;

        $success = DB::transaction(function () use ($ticket, $adminId) {
            $affected = Ticket::where('id', $ticket->id)
                ->where('status', Ticket::STATUS_ACTIVE)
                ->update([
                    'status' => Ticket::STATUS_USED,
                    'used_at' => now(),
                    'used_by' => $adminId,
                ]);

            if ($affected > 0) {
                $ticket->refresh();
                \App\Models\AuditLog::log('ticket_used', $ticket, 'Used by gate man: ' . $request->user()->name);
                return true;
            }

            return false;
        });

        if ($success) {
            return redirect()->route('gate.dashboard')->with('success', __('Ticket verified and entry allowed.'));
        }

        return redirect()->route('gate.dashboard')->with('error', __('This ticket has already been used or is not valid for entry.'));
    }

    protected function showResult(?Ticket $ticket, string $scannedValue)
    {
        if (! $ticket) {
            return view('gate.result', ['status' => 'invalid', 'message' => __('INVALID TICKET'), 'message_ar' => 'تذكرة غير صالحة', 'ticket' => null]);
        }

        if ($ticket->status === Ticket::STATUS_USED) {
            return view('gate.result', ['status' => 'used', 'message' => __('ALREADY USED'), 'message_ar' => 'التذكرة مستخدمة بالفعل', 'ticket' => $ticket]);
        }

        if ($ticket->status === Ticket::STATUS_CANCELLED) {
            return view('gate.result', ['status' => 'cancelled', 'message' => __('CANCELLED TICKET'), 'message_ar' => 'تذكرة ملغاة', 'ticket' => $ticket]);
        }

        return view('gate.result', ['status' => 'valid', 'message' => __('VALID TICKET'), 'message_ar' => 'تذكرة صالحة', 'ticket' => $ticket]);
    }
}
