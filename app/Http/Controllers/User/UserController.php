<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\HealthCard;
use App\Models\Hospital;
use App\Models\HospitalService;
use App\Models\PatientAvailment;
use App\Models\Notification;
use App\Models\SupportTicket;

class UserController extends Controller
{
    /** DASHBOARD */
    public function dashboard()
    {
        $user = Auth::user();

        $healthCard = $user->healthCard;

        $notifications = $user->notifications()
                              ->latest()
                              ->take(5)
                              ->get();

        $recentAvailments = $user->availments()
                                 ->with(['hospital', 'service'])
                                 ->latest()
                                 ->take(5)
                                 ->get();

        $totalSavings = $user->availments()->sum('discount_amount');

        return view('user.dashboard', compact(
            'user', 'healthCard', 'notifications', 'recentAvailments', 'totalSavings'
        ));
    }

    /** PROFILE */
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|digits:10|unique:users,mobile,' . $user->id,
            'address' => 'required|string|max:500',
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->address = $request->address;
        $user->blood_group = $request->blood_group;

        if ($request->hasFile('photo')) {
            if ($user->photo_path) {
                Storage::disk('public')->delete($user->photo_path);
            }
            $user->photo_path = $request->file('photo')->store('photos', 'public');
        }

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect']);
            }
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    /** HEALTH CARD */
    public function showHealthCard()
    {
        $healthCard = Auth::user()->healthCard;

        if (!$healthCard) {
            return redirect()->route('user.dashboard')
                             ->with('error', 'Health card not found.');
        }

        return view('user.health-card', compact('healthCard'));
    }

    public function downloadHealthCard()
    {
        $healthCard = Auth::user()->healthCard;

        if (!$healthCard || !Storage::disk('public')->exists($healthCard->pdf_path)) {
            return back()->withErrors(['error' => 'Health card not found.']);
        }

        return Storage::disk('public')->download($healthCard->pdf_path, 'HealthCard_' . Auth::user()->name . '.pdf');
    }

    /** HOSPITALS */
    public function hospitals(Request $request)
    {
        $query = Hospital::where('status', 'approved');

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        if ($request->filled('service_id')) {
            $query->whereHas('services', function ($q) use ($request) {
                $q->where('service_id', $request->service_id);
            });
        }

        if ($request->filled('discount_min')) {
            $query->whereHas('services', function ($q) use ($request) {
                $q->where('discount_percentage', '>=', $request->discount_min);
            });
        }

        $hospitals = $query->with('services')->paginate(10);

        return view('user.hospitals', compact('hospitals'));
    }

    public function hospitalDetail($id)
    {
        $hospital = Hospital::with('services')->findOrFail($id);
        return view('user.hospital-detail', compact('hospital'));
    }

    /** DISCOUNT HISTORY */
    public function discounts()
    {
        $user = Auth::user();
        $availments = PatientAvailment::where('user_id', $user->id)
                                      ->with(['hospital', 'service'])
                                      ->latest()
                                      ->paginate(10);

        $totalSavings = $user->availments()->sum('discount_amount');

        return view('user.discounts', compact('availments', 'totalSavings'));
    }

    /** NOTIFICATIONS */
    public function notifications()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(20);
        return view('user.notifications', compact('notifications'));
    }

    public function markNotificationRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->update(['is_read' => true]);
        }

        return response()->json(['status' => 'success']);
    }

    public function markAllNotificationsRead()
    {
        Auth::user()->notifications()->where('is_read', false)->update(['is_read' => true]);
        return back()->with('success', 'All notifications marked as read');
    }

    /** SUPPORT TICKETS */
    public function supportTickets()
    {
        $tickets = Auth::user()->supportTickets()->latest()->paginate(10);
        return view('user.support', compact('tickets'));
    }

    public function createSupportTicket(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ]);

        $ticket = SupportTicket::create([
            'user_id' => Auth::id(),
            'ticket_number' => 'TKT' . strtoupper(uniqid()),
            'subject' => $request->subject,
            'message' => $request->message,
            'priority' => $request->priority,
            'status' => 'open',
        ]);

        return back()->with('success', 'Support ticket created successfully! Ticket Number: ' . $ticket->ticket_number);
    }

    public function showSupportTicket($id)
    {
        $ticket = Auth::user()->supportTickets()->findOrFail($id);
        return view('user.support-detail', compact('ticket'));
    }
}
