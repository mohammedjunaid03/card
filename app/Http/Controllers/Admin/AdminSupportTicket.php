<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class AdminSupportTicket extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::with(['user', 'hospital'])
            ->latest()
            ->paginate(20);

        return view('admin.support-tickets.index', compact('tickets'));
    }

    public function show($id)
    {
        $ticket = SupportTicket::with(['user', 'hospital'])->findOrFail($id);
        
        return view('admin.support-tickets.show', compact('ticket'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'response' => 'nullable|string',
        ]);

        $ticket = SupportTicket::findOrFail($id);
        $ticket->update([
            'status' => $request->status,
            'admin_response' => $request->response,
            'resolved_at' => $request->status === 'resolved' ? now() : null,
        ]);

        return redirect()->back()->with('success', 'Ticket updated successfully.');
    }
}
