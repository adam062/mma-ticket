<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketType;
use Illuminate\Http\Request;

class TicketTypeController extends Controller
{
    public function index()
    {
        $ticketTypes = TicketType::orderBy('sort_order')->get();
        return view('admin.ticket-types.index', compact('ticketTypes'));
    }

    public function create()
    {
        return view('admin.ticket-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'price' => 'required|integer|min:1',
            'capacity' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        TicketType::create($validated);

        return redirect()->route('admin.ticket-types.index')->with('success', __('Ticket type created.'));
    }

    public function edit(TicketType $ticketType)
    {
        return view('admin.ticket-types.edit', compact('ticketType'));
    }

    public function update(Request $request, TicketType $ticketType)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'price' => 'required|integer|min:1',
            'capacity' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $ticketType->update($validated);

        return redirect()->route('admin.ticket-types.index')->with('success', __('Ticket type updated.'));
    }
}
