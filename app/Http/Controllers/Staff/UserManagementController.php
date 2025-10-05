<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CardGeneratorService;
use App\Services\EncryptionService;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    protected $cardGeneratorService;
    protected $encryptionService;

    public function __construct(CardGeneratorService $cardGeneratorService, EncryptionService $encryptionService)
    {
        $this->cardGeneratorService = $cardGeneratorService;
        $this->encryptionService = $encryptionService;
    }

    public function create()
    {
        return view('staff.register-user');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'age' => 'required|integer|min:1|max:150',
            'gender' => 'required|in:Male,Female,Other',
            'address' => 'required|string',
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|digits:10|unique:users,mobile',
            'password' => 'required|string|min:8|confirmed',
            'aadhaar' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        // Encrypt Aadhaar
        $tempAadhaarPath = $request->file('aadhaar')->store('aadhaar_temp', 'private');
        $aadhaarPath = $this->encryptionService->encryptAndStoreFile($tempAadhaarPath, 'private');

        $photoPath = $request->hasFile('photo') ? $request->file('photo')->store('photos', 'public') : null;

        $user = User::create([
            'name' => $request->name,
            'date_of_birth' => $request->date_of_birth,
            'age' => $request->age,
            'gender' => $request->gender,
            'address' => $request->address,
            'blood_group' => $request->blood_group,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => $request->password,
            'aadhaar_path' => $aadhaarPath,
            'photo_path' => $photoPath,
            'email_verified' => true,
            'mobile_verified' => true,
            'status' => 'active',
        ]);

        // Generate Health Card
        $this->cardGeneratorService->generateCard($user);

        return redirect()->route('staff.dashboard')
                        ->with('success', 'User registered successfully! Health card generated.');
    }
}
