<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's health card
        $healthCard = $user->healthCard;
        
        // Get recent availments (last 10 for better display)
        $recentAvailments = $user->availments()
            ->with(['hospital', 'service'])
            ->orderBy('availment_date', 'desc')
            ->limit(10)
            ->get();
        
        // Calculate total savings
        $totalSavings = $user->availments()->sum('discount_amount');
        
        // Get recent notifications (last 5)
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get additional statistics
        $totalVisits = $user->availments()->count();
        $totalHospitals = $user->availments()->distinct('hospital_id')->count();
        $averageSavings = $totalVisits > 0 ? $totalSavings / $totalVisits : 0;
        
        // Get monthly savings data for charts (last 6 months)
        $monthlySavings = $user->availments()
            ->selectRaw('DATE_FORMAT(availment_date, "%Y-%m") as month, SUM(discount_amount) as savings')
            ->where('availment_date', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        return view('user.dashboard', compact(
            'user',
            'healthCard',
            'recentAvailments',
            'totalSavings',
            'notifications',
            'totalVisits',
            'totalHospitals',
            'averageSavings',
            'monthlySavings'
        ));
    }
}