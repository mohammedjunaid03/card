<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use App\Models\Hospital;
use App\Models\Staff;
use Illuminate\Support\Facades\Mail;

class AdminNotification extends Controller
{
    public function index()
    {
        $notifications = Notification::with(['user', 'hospital', 'staff'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,success,error',
            'target_type' => 'required|in:all,users,hospitals,staff,specific',
            'target_ids' => 'required_if:target_type,specific|array',
            'target_ids.*' => 'integer'
        ]);

        $notificationData = [
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
        ];

        switch ($request->target_type) {
            case 'all':
                // Send to all users
                $users = User::where('status', 'active')->get();
                foreach ($users as $user) {
                    Notification::create(array_merge($notificationData, [
                        'recipient_type' => 'App\\Models\\User',
                        'recipient_id' => $user->id
                    ]));
                }

                // Send to all hospitals
                $hospitals = Hospital::where('status', 'active')->get();
                foreach ($hospitals as $hospital) {
                    Notification::create(array_merge($notificationData, [
                        'recipient_type' => 'App\\Models\\Hospital',
                        'recipient_id' => $hospital->id
                    ]));
                }

                // Send to all staff
                $staff = Staff::where('status', 'active')->get();
                foreach ($staff as $staffMember) {
                    Notification::create(array_merge($notificationData, [
                        'recipient_type' => 'App\\Models\\Staff',
                        'recipient_id' => $staffMember->id
                    ]));
                }
                break;

            case 'users':
                $users = User::where('status', 'active')->get();
                foreach ($users as $user) {
                    Notification::create(array_merge($notificationData, [
                        'recipient_type' => 'App\\Models\\User',
                        'recipient_id' => $user->id
                    ]));
                }
                break;

            case 'hospitals':
                $hospitals = Hospital::where('status', 'active')->get();
                foreach ($hospitals as $hospital) {
                    Notification::create(array_merge($notificationData, [
                        'recipient_type' => 'App\\Models\\Hospital',
                        'recipient_id' => $hospital->id
                    ]));
                }
                break;

            case 'staff':
                $staff = Staff::where('status', 'active')->get();
                foreach ($staff as $staffMember) {
                    Notification::create(array_merge($notificationData, [
                        'recipient_type' => 'App\\Models\\Staff',
                        'recipient_id' => $staffMember->id
                    ]));
                }
                break;

            case 'specific':
                foreach ($request->target_ids as $targetId) {
                    Notification::create(array_merge($notificationData, [
                        'recipient_type' => 'App\\Models\\User',
                        'recipient_id' => $targetId
                    ]));
                }
                break;
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notifications sent successfully.');
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notification deleted successfully.');
    }
}