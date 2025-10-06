<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\SupportTicket;

class SupportTicketController extends Controller
{
    public function index()
    {
        $user = Auth::guard('web')->user();
        $tickets = SupportTicket::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.support-tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('user.support-tickets.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::guard('web')->user();

        SupportTicket::create([
            'user_id' => $user->id,
            'subject' => $request->subject,
            'message' => $request->message,
            'priority' => $request->priority,
            'status' => 'open',
        ]);

        return redirect()->route('user.support-tickets.index')
            ->with('success', 'Support ticket created successfully!');
    }

    public function show($id)
    {
        $user = Auth::guard('web')->user();
        $ticket = SupportTicket::where('user_id', $user->id)
            ->findOrFail($id);

        return view('user.support-tickets.show', compact('ticket'));
    }
}
