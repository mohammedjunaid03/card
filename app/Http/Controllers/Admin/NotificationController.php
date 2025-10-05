<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $users = User::where('status', 'active')->get(['id', 'name', 'email']);
        $recentNotifications = Notification::latest()->take(10)->get();
        
        return view('admin.notifications', compact('users', 'recentNotifications'));
    }
    
    public function send(Request $request)
    {
        $request->validate([
            'recipient_type' => 'required|in:all,user,hospital,staff',
            'recipient_id' => 'nullable|required_if:recipient_type,user',
            'type' => 'required|in:info,success,warning,error',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        if ($request->recipient_type === 'all') {
            // Send to all users
            $users = User::where('status', 'active')->get();
            foreach ($users as $user) {
                Notification::create([
                    'recipient_type' => 'user',
                    'recipient_id' => $user->id,
                    'title' => $request->title,
                    'message' => $request->message,
                    'type' => $request->type,
                ]);
            }
        } else {
            Notification::create([
                'recipient_type' => $request->recipient_type,
                'recipient_id' => $request->recipient_id,
                'title' => $request->title,
                'message' => $request->message,
                'type' => $request->type,
            ]);
        }
        
        return back()->with('success', 'Notification sent successfully!');
    }
}