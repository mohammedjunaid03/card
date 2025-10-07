<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\HealthCard;
use App\Models\User;
use Illuminate\Http\Request;

class CardVerificationController extends Controller
{
    public function showVerificationForm()
    {
        return view('hospital.verify-card');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'card_number' => 'required|string',
        ]);

        $cardNumber = $request->input('card_number');
        
        // Try to find the health card
        $healthCard = HealthCard::where('card_number', $cardNumber)
            ->where('status', 'active')
            ->with('user')
            ->first();

        if (!$healthCard) {
            return back()->withErrors(['card_number' => 'Invalid or inactive health card.']);
        }

        // Check if card is expired
        if ($healthCard->isExpired()) {
            return back()->withErrors(['card_number' => 'This health card has expired.']);
        }

        return view('hospital.card-details', compact('healthCard'));
    }
}
