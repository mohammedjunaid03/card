<?php

namespace App\Services;

use App\Models\User;
use App\Models\Hospital;
use App\Models\HealthCard;
use App\Models\PatientAvailment;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ReportService
{
    public function getDashboardStats(): array
    {
        return [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'total_hospitals' => Hospital::where('status', 'active')->count(),
            'total_cards_issued' => HealthCard::count(),
            'active_cards' => HealthCard::where('status', 'active')->count(),
            'total_availments' => PatientAvailment::count(),
            'total_discount_given' => PatientAvailment::sum('discount_amount'),
        ];
    }

    public function getMonthlyTrends(int $months = 6): array
    {
        $startDate = Carbon::now()->subMonths($months);

        $userTrends = User::select(
            DB::raw('strftime("%Y-%m", created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', $startDate)
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        $cardTrends = HealthCard::select(
            DB::raw('strftime("%Y-%m", issued_date) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('issued_date', '>=', $startDate)
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        return [
            'users' => $userTrends,
            'cards' => $cardTrends,
        ];
    }

    public function getHospitalAnalytics(int $hospitalId): array
    {
        $hospital = Hospital::findOrFail($hospitalId);

        $totalPatients = PatientAvailment::where('hospital_id', $hospitalId)
                            ->distinct()
                            ->count('user_id');

        $totalAvailments = PatientAvailment::where('hospital_id', $hospitalId)->count();

        $totalDiscountGiven = PatientAvailment::where('hospital_id', $hospitalId)
                                              ->sum('discount_amount');

        $serviceWiseData = PatientAvailment::where('hospital_id', $hospitalId)
            ->select('service_id', 
                     DB::raw('COUNT(*) as count'),
                     DB::raw('SUM(discount_amount) as total_discount'))
            ->with('service')
            ->groupBy('service_id')
            ->get();

        return [
            'total_patients' => $totalPatients,
            'total_availments' => $totalAvailments,
            'total_discount_given' => $totalDiscountGiven,
            'service_wise_data' => $serviceWiseData,
        ];
    }

    public function exportUsersReport(string $format = 'xlsx')
    {
        $users = User::with('healthCard')->get();

        return Excel::download(new \App\Exports\UsersExport($users), 
                               'users_report.' . $format);
    }
}
