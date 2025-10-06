<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HealthCard;
use App\Models\PatientAvailment;
use App\Models\Notification;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::guard('web')->user();
        
        // Get user's health card
        $healthCard = HealthCard::where('user_id', $user->id)->first();
        
        // Get recent discount history
        $recentDiscounts = PatientAvailment::where('health_card_id', $healthCard?->id)
            ->with(['hospital', 'service'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get unread notifications
        $notifications = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('user.dashboard', compact('user', 'healthCard', 'recentDiscounts', 'notifications'));
    }
}
