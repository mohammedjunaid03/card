<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PatientAvailment;
use App\Models\HealthCard;

class DiscountHistoryController extends Controller
{
    public function index()
    {
        $user = Auth::guard('web')->user();
        $healthCard = HealthCard::where('user_id', $user->id)->first();

        $discounts = PatientAvailment::where('health_card_id', $healthCard?->id)
            ->with(['hospital', 'service'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('user.discount-history', compact('discounts'));
    }
}
