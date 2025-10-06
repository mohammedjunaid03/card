<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hospital;
use App\Models\HealthCard;
use App\Models\PatientAvailment;
use App\Models\Service;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Total counts
        $totalUsers = User::count();
        $totalHospitals = Hospital::where('status', 'active')->count();
        $totalCards = HealthCard::count();
        $totalServices = Service::count();

        // Cards issued trend (last 12 months)
        $cardsIssuedTrend = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $count = HealthCard::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            
            $cardsIssuedTrend[] = [
                'month' => $month->format('M Y'),
                'count' => $count
            ];
        }

        // Hospitals by city
        $hospitalsByCity = Hospital::where('status', 'active')
            ->selectRaw('city, count(*) as count')
            ->groupBy('city')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        // Most availed services
        $topServices = PatientAvailment::with('service')
            ->selectRaw('service_id, count(*) as count, sum(discount_amount) as total_discount')
            ->groupBy('service_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        // Active vs Inactive users
        $activeUsers = User::where('status', 'active')->count();
        $inactiveUsers = User::where('status', '!=', 'active')->count();

        // Financial impact
        $totalDiscountGiven = PatientAvailment::sum('discount_amount');
        $totalRevenue = PatientAvailment::sum('final_amount');

        return view('admin.analytics', compact(
            'totalUsers',
            'totalHospitals',
            'totalCards',
            'totalServices',
            'cardsIssuedTrend',
            'hospitalsByCity',
            'topServices',
            'activeUsers',
            'inactiveUsers',
            'totalDiscountGiven',
            'totalRevenue'
        ));
    }
}
