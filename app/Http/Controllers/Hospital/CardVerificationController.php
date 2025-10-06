<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\HealthCard;

class CardVerificationController extends Controller
{
    public function showVerificationForm()
    {
        return view('hospital.verify-card');
    }

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'card_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $healthCard = HealthCard::with('user')
            ->where('card_number', $request->card_id)
            ->orWhere('qr_code', $request->card_id)
            ->first();

        if (!$healthCard) {
            return redirect()->back()
                ->with('error', 'Invalid health card. Please check the card number/QR code.')
                ->withInput();
        }

        if ($healthCard->status !== 'active') {
            return redirect()->back()
                ->with('error', 'This health card is not active.')
                ->withInput();
        }

        // Check validity
        if ($healthCard->valid_till < now()) {
            return redirect()->back()
                ->with('error', 'This health card has expired.')
                ->withInput();
        }

        return view('hospital.card-verified', compact('healthCard'));
    }
}
