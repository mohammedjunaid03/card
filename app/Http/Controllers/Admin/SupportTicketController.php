<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    /**
     * Display a listing of support tickets for Admin, with filters.
     */
    public function index(Request $request)
    {
        $query = SupportTicket::with('user')->latest();
        
        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }
        
        $tickets = $query->paginate(20);
        
        return view('admin.support.index', compact('tickets'));
    }

    /**
     * Display the specified support ticket and its user's details.
     */
    public function show($id)
    {
        $ticket = SupportTicket::with('user')->findOrFail($id);
        
        return view('admin.support.show', compact('ticket'));
    }

    /**
     * Update the ticket status and add an admin response.
     */
    public function update(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:open,in_progress,closed',
            'admin_response' => 'required|string',
        ]);
        
        $updateData = [
            'status' => $request->status,
            'admin_response' => $request->admin_response,
        ];
        
        // Automatically set the resolved_at timestamp when closing a ticket
        if ($request->status === 'closed' && !$ticket->resolved_at) {
            $updateData['resolved_at'] = now();
        } elseif ($request->status !== 'closed') {
             // If reopened or in progress, ensure resolved_at is null
             $updateData['resolved_at'] = null;
        }

        $ticket->update($updateData);

        return back()->with('success', 'Ticket ' . $ticket->ticket_number . ' updated successfully.');
    }
}