<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Services\CardGeneratorService;
use Barryvdh\DomPDF\Facade\Pdf;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with('healthCard')->orderBy('created_at', 'desc')->paginate(20);
        return view('staff.users.index', compact('users'));
    }

    public function create()
    {
        return view('staff.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'mobile' => 'required|string|unique:users',
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
            'email_verified_at' => now(),
            'registered_by' => auth()->id(),
            'registration_type' => 'staff'
        ]);

        // Handle Aadhaar file upload
        if ($request->hasFile('aadhaar_file')) {
            $file = $request->file('aadhaar_file');
            $filename = 'aadhaar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('aadhaar', $filename, 'private');
            $user->update(['aadhaar_file' => $path]);
        }

        // Automatically generate health card
        try {
            $cardGenerator = new CardGeneratorService();
            $healthCard = $cardGenerator->generateCard($user);
            
            return redirect()->route('staff.dashboard')
                ->with('success', 'User registered successfully and health card generated!');
        } catch (\Exception $e) {
            // If card generation fails, still redirect with success but log the error
            \Log::error('Health card generation failed for user ' . $user->id . ': ' . $e->getMessage());
            
            return redirect()->route('staff.dashboard')
                ->with('success', 'User registered successfully. Health card generation failed - please generate manually.');
        }
    }

    /**
     * Download health card PDF for a user
     */
    public function downloadHealthCard(User $user)
    {
        $healthCard = $user->healthCard;
        
        if (!$healthCard) {
            return redirect()->back()->with('error', 'Health card not found for this user.');
        }

        if (!$healthCard->pdf_path || !file_exists(storage_path('app/public/' . $healthCard->pdf_path))) {
            return redirect()->back()->with('error', 'Health card PDF not found. Please regenerate the card.');
        }

        return response()->download(storage_path('app/public/' . $healthCard->pdf_path), 
            'health-card-' . $user->name . '.pdf');
    }

    /**
     * Print health card PDF for a user
     */
    public function printHealthCard(User $user)
    {
        $healthCard = $user->healthCard;
        
        if (!$healthCard) {
            return redirect()->back()->with('error', 'Health card not found for this user.');
        }

        if (!$healthCard->pdf_path || !file_exists(storage_path('app/public/' . $healthCard->pdf_path))) {
            return redirect()->back()->with('error', 'Health card PDF not found. Please regenerate the card.');
        }

        return response()->file(storage_path('app/public/' . $healthCard->pdf_path));
    }

    /**
     * Manually generate health card for a user
     */
    public function generateHealthCard(User $user)
    {
        // Check if user already has a health card
        if ($user->healthCard) {
            return redirect()->back()->with('error', 'Health card already exists for this user.');
        }

        try {
            $cardGenerator = new CardGeneratorService();
            $healthCard = $cardGenerator->generateCard($user);
            
            return redirect()->back()->with('success', 'Health card generated successfully!');
        } catch (\Exception $e) {
            \Log::error('Manual health card generation failed for user ' . $user->id . ': ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Failed to generate health card. Please try again.');
        }
    }
}