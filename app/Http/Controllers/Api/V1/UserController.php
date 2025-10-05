<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\HealthCard;
use App\Services\EncryptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $encryptionService;

    public function __construct(EncryptionService $encryptionService)
    {
        $this->encryptionService = $encryptionService;
    }

    // Fetch user profile
    public function profile(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        return response()->json(['user' => $user]);
    }

    // Fetch health card
    public function healthCard(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        $card = HealthCard::where('user_id', $user->id)->first();

        if (!$card) {
            return response()->json(['error' => 'No health card found'], 404);
        }

        return response()->json(['card' => $card]);
    }

    // Upload Aadhaar / Photo
    public function uploadDocuments(Request $request)
    {
        $user = Auth::guard('sanctum')->user();

        $validator = Validator::make($request->all(), [
            'aadhaar' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'photo'   => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Handle Aadhaar file
        $tempAadhaarPath = $request->file('aadhaar')->store('aadhaar_temp', 'private');
        $aadhaarPath = $this->encryptionService->encryptAndStoreFile($tempAadhaarPath, 'private');

        // Handle optional photo
        $photoPath = $request->hasFile('photo') 
            ? $request->file('photo')->store('photos', 'public')
            : $user->photo_path;

        $user->update([
            'aadhaar_path' => $aadhaarPath,
            'photo_path'   => $photoPath,
        ]);

        return response()->json([
            'message' => 'Documents uploaded successfully',
            'aadhaar_path' => $aadhaarPath,
            'photo_path' => $photoPath,
        ]);
    }
}
