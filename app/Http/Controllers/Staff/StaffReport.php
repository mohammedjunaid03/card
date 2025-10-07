<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hospital;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffReport extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalHospitals = Hospital::count();
        $totalStaff = Staff::count();
        
        $recentUsers = User::latest()->limit(10)->get();
        $recentHospitals = Hospital::latest()->limit(10)->get();
        
        $userStats = User::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
            
        $hospitalStats = Hospital::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        return view('staff.reports.index', compact(
            'totalUsers',
            'totalHospitals', 
            'totalStaff',
            'recentUsers',
            'recentHospitals',
            'userStats',
            'hospitalStats'
        ));
    }
}
