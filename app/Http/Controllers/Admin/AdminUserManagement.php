<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\HealthCard;
use App\Services\CardGeneratorService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminUserManagement extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['healthCard']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by card status
        if ($request->filled('card_status')) {
            if ($request->card_status === 'issued') {
                $query->whereHas('healthCard');
            } else {
                $query->whereDoesntHave('healthCard');
            }
        }

        // Search by name, email, or mobile
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('mobile', 'like', '%' . $search . '%');
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'mobile' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
            'blood_group' => 'required|string',
            'password' => 'required|string|min:8',
            'aadhaar_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'date_of_birth' => $request->date_of_birth,
            'age' => \Carbon\Carbon::parse($request->date_of_birth)->age,
            'gender' => $request->gender,
            'address' => $request->address,
            'blood_group' => $request->blood_group,
            'password' => Hash::make($request->password),
            'status' => 'active',
            'email_verified_at' => now()
        ]);

        // Handle Aadhaar file upload
        if ($request->hasFile('aadhaar_file')) {
            $file = $request->file('aadhaar_file');
            $filename = 'aadhaar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('aadhaar', $filename, 'private');
            $user->update(['aadhaar_file' => $path]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show($id)
    {
        $user = User::with(['healthCard', 'availments.hospital', 'availments.service'])
            ->findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'mobile' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
            'blood_group' => 'required|string',
            'status' => 'required|in:active,inactive,suspended'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'date_of_birth' => $request->date_of_birth,
            'age' => \Carbon\Carbon::parse($request->date_of_birth)->age,
            'gender' => $request->gender,
            'address' => $request->address,
            'blood_group' => $request->blood_group,
            'status' => $request->status
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:active,inactive,suspended'
        ]);

        $user->update(['status' => $request->status]);

        return response()->json(['success' => true]);
    }

    public function issueHealthCard(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->healthCard) {
            return response()->json(['error' => 'User already has a health card.'], 400);
        }

        $cardService = new CardGeneratorService();
        $healthCard = $cardService->generateHealthCard($user);

        return response()->json(['success' => true, 'card_id' => $healthCard->id]);
    }

    public function bulkIssueHealthCards(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $cardService = new CardGeneratorService();
        $issued = 0;

        foreach ($request->user_ids as $userId) {
            $user = User::find($userId);
            if ($user && !$user->healthCard) {
                $cardService->generateHealthCard($user);
                $issued++;
            }
        }

        return response()->json(['success' => true, 'issued' => $issued]);
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'status' => 'required|in:active,inactive,suspended'
        ]);

        User::whereIn('id', $request->user_ids)
            ->update(['status' => $request->status]);

        return response()->json(['success' => true]);
    }
}