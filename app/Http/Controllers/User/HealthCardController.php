<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthCardController extends Controller
{
    public function index()
    {
        return view('user.health-card');
    }

    public function show()
    {
        $user = Auth::user();
        $healthCard = $user->healthCard;
        
        if (!$healthCard) {
            return redirect()->route('user.dashboard')
                ->with('error', 'No health card found. Please contact support.');
        }
        
        return view('user.health-card', compact('healthCard'));
    }

    public function download()
    {
        $user = Auth::user();
        $healthCard = $user->healthCard;
        
        if (!$healthCard || !$healthCard->pdf_path) {
            return redirect()->back()
                ->with('error', 'Health card PDF not available.');
        }
        
        $filePath = storage_path('app/public/' . $healthCard->pdf_path);
        
        if (!file_exists($filePath)) {
            return redirect()->back()
                ->with('error', 'Health card PDF file not found.');
        }
        
        return response()->download($filePath, 'health-card-' . $healthCard->card_number . '.pdf');
    }
}