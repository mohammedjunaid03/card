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

class AdminAnalytics extends Controller
{
    public function index(Request $request)
    {
        // Date range for analytics
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());

        // Total statistics
        $totalUsers = User::count();
        $totalHospitals = Hospital::where('status', 'approved')->count();
        $totalHealthCards = HealthCard::count();
        $totalAvailments = PatientAvailment::count();
        $totalDiscountGiven = PatientAvailment::sum('discount_amount');

        // Monthly trends
        $monthlyUsers = User::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyHospitals = Hospital::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('status', 'approved')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyAvailments = PatientAvailment::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count, SUM(discount_amount) as total_discount')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top hospitals by availments
        $topHospitals = Hospital::withCount('availments')
            ->withSum('availments', 'discount_amount')
            ->where('status', 'approved')
            ->orderBy('availments_count', 'desc')
            ->limit(10)
            ->get();

        // Most availed services
        $topServices = Service::withCount('availments')
            ->withSum('availments', 'discount_amount')
            ->orderBy('availments_count', 'desc')
            ->limit(10)
            ->get();

        // User status distribution
        $userStatusDistribution = User::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Hospital status distribution
        $hospitalStatusDistribution = Hospital::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Recent activity
        $recentUsers = User::orderBy('created_at', 'desc')->limit(5)->get();
        $recentHospitals = Hospital::where('status', 'approved')->orderBy('created_at', 'desc')->limit(5)->get();
        $recentAvailments = PatientAvailment::with(['user', 'hospital', 'service'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Financial impact
        $totalSavings = PatientAvailment::sum('discount_amount');
        $averageDiscount = PatientAvailment::avg('discount_amount');
        $totalTransactions = PatientAvailment::count();

        // Hospital status distribution for charts
        $hospitalStats = Hospital::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        return view('admin.analytics', compact(
            'totalUsers',
            'totalHospitals',
            'totalHealthCards',
            'totalAvailments',
            'totalDiscountGiven',
            'monthlyUsers',
            'monthlyHospitals',
            'monthlyAvailments',
            'topHospitals',
            'topServices',
            'userStatusDistribution',
            'hospitalStatusDistribution',
            'recentUsers',
            'recentHospitals',
            'recentAvailments',
            'totalSavings',
            'averageDiscount',
            'totalTransactions',
            'hospitalStats',
            'startDate',
            'endDate'
        ));
    }
}