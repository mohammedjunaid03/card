<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hospital;
use App\Models\HealthCard;

class DashboardController extends Controller
{
    /**
     * Display the Staff dashboard overview.
     */
    public function index()
    {
        // Pending approvals
        $pendingUserApprovals = User::where('status', 'pending')->count();
        $pendingHospitalApprovals = Hospital::where('status', 'pending')->count();

        // Total counts
        $totalUsers = User::count();
        $totalHospitals = Hospital::where('status', 'approved')->count();
        $totalCardsIssued = HealthCard::count();

        $stats = [
            'total_users' => $totalUsers,
            'total_hospitals' => $totalHospitals,
            'total_cards_issued' => $totalCardsIssued,
            'pending_user_cards' => $pendingUserApprovals,
            'pending_hospital_approvals' => $pendingHospitalApprovals,
        ];

        // Recent registrations
        $recentRegistrations = User::latest()->take(5)->get();

        return view('staff.dashboard', compact('stats', 'recentRegistrations'));
    }
}
