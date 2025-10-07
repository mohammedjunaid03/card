<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $tickets = SupportTicket::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('user.support-tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('user.support-tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'category' => 'required|in:technical,billing,general,card_issue'
        ]);

        $user = Auth::user();

        $ticket = SupportTicket::create([
            'user_id' => $user->id,
            'subject' => $request->subject,
            'message' => $request->message,
            'priority' => $request->priority,
            'category' => $request->category,
            'status' => 'open'
        ]);

        return redirect()->route('user.support-tickets.show', $ticket->id)
            ->with('success', 'Support ticket created successfully.');
    }

    public function show($id)
    {
        $user = Auth::user();
        
        $ticket = SupportTicket::where('id', $id)
            ->where('user_id', $user->id)
            ->with('replies')
            ->firstOrFail();

        return view('user.support-tickets.show', compact('ticket'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        
        $ticket = SupportTicket::where('id', $id)
            ->where('user_id', $user->id)
            ->where('status', 'open')
            ->firstOrFail();

        return view('user.support-tickets.edit', compact('ticket'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'category' => 'required|in:technical,billing,general,card_issue'
        ]);

        $user = Auth::user();
        
        $ticket = SupportTicket::where('id', $id)
            ->where('user_id', $user->id)
            ->where('status', 'open')
            ->firstOrFail();

        $ticket->update([
            'subject' => $request->subject,
            'message' => $request->message,
            'priority' => $request->priority,
            'category' => $request->category
        ]);

        return redirect()->route('user.support-tickets.show', $ticket->id)
            ->with('success', 'Support ticket updated successfully.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        
        $ticket = SupportTicket::where('id', $id)
            ->where('user_id', $user->id)
            ->where('status', 'open')
            ->firstOrFail();

        $ticket->delete();

        return redirect()->route('user.support-tickets.index')
            ->with('success', 'Support ticket deleted successfully.');
    }
}
