<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hospital;
use App\Models\HealthCard;
use App\Models\PatientAvailment;
use App\Models\Service;
use App\Exports\UsersExport;
use App\Exports\HospitalsExport;
use App\Exports\StaffExport;
use App\Exports\AvailmentsExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class AdminReport extends Controller
{
    public function index(Request $request)
    {
        // Date range for reports
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());

        // User reports
        $userStats = [
            'total' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
            'suspended' => User::where('status', 'suspended')->count(),
            'with_cards' => User::whereHas('healthCard')->count(),
            'without_cards' => User::whereDoesntHave('healthCard')->count(),
            'new_this_month' => User::whereBetween('created_at', [$startDate, $endDate])->count()
        ];

        // Hospital reports
        $hospitalStats = [
            'total' => Hospital::count(),
            'approved' => Hospital::where('status', 'approved')->count(),
            'pending' => Hospital::where('status', 'pending')->count(),
            'active' => Hospital::where('status', 'approved')->count(),
            'inactive' => Hospital::where('status', 'rejected')->count(),
            'new_this_month' => Hospital::whereBetween('created_at', [$startDate, $endDate])->count()
        ];

        // Availment reports
        $availmentStats = [
            'total' => PatientAvailment::count(),
            'this_month' => PatientAvailment::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_discount' => PatientAvailment::sum('discount_amount'),
            'monthly_discount' => PatientAvailment::whereBetween('created_at', [$startDate, $endDate])->sum('discount_amount'),
            'average_discount' => PatientAvailment::avg('discount_amount')
        ];

        // Top performing hospitals
        $topHospitals = Hospital::withCount('availments')
            ->withSum('availments', 'discount_amount')
            ->where('status', 'approved')
            ->orderBy('availments_count', 'desc')
            ->limit(10)
            ->get();

        // Most popular services
        $popularServices = Service::withCount('availments')
            ->withSum('availments', 'discount_amount')
            ->orderBy('availments_count', 'desc')
            ->limit(10)
            ->get();

        // Monthly trends
        $monthlyTrends = PatientAvailment::selectRaw('
                DATE_FORMAT(created_at, "%Y-%m") as month,
                COUNT(*) as availments_count,
                SUM(discount_amount) as total_discount,
                AVG(discount_amount) as avg_discount
            ')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Extract individual values for the view
        $totalUsers = $userStats['total'];
        $activeUsers = $userStats['active'];
        $totalHospitals = $hospitalStats['total'];
        $approvedHospitals = $hospitalStats['approved'];
        $totalHealthCards = $userStats['with_cards'];
        $totalAvailments = $availmentStats['total'];
        $monthlyRegistrations = $userStats['new_this_month'];
        $monthlyAvailments = $availmentStats['this_month'];
        $recentAvailments = PatientAvailment::with(['user', 'hospital', 'service'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Additional data for charts and tables
        $monthlyUsers = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return (object) [
                    'month' => (int) $item->month,
                    'count' => $item->count
                ];
            });

        $monthlyAvailmentsData = PatientAvailment::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return (object) [
                    'month' => (int) $item->month,
                    'count' => $item->count
                ];
            });

        $hospitalStats = Hospital::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Additional missing variables for the view
        $inactiveUsers = $userStats['inactive'];
        $suspendedUsers = $userStats['suspended'];
        $usersWithCards = $userStats['with_cards'];
        $usersWithoutCards = $userStats['without_cards'];
        $pendingHospitals = $hospitalStats->where('status', 'pending')->first()->count ?? 0;
        $rejectedHospitals = $hospitalStats->where('status', 'rejected')->first()->count ?? 0;
        $totalServices = Service::count();
        $activeServices = Service::where('is_active', true)->count();
        $topServices = Service::withCount('availments')
            ->withSum('availments', 'discount_amount')
            ->orderBy('availments_count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.reports.index', compact(
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'suspendedUsers',
            'totalHospitals',
            'approvedHospitals',
            'pendingHospitals',
            'rejectedHospitals',
            'totalHealthCards',
            'usersWithCards',
            'usersWithoutCards',
            'totalAvailments',
            'totalServices',
            'activeServices',
            'monthlyRegistrations',
            'monthlyAvailments',
            'monthlyAvailmentsData',
            'topHospitals',
            'topServices',
            'recentAvailments',
            'popularServices',
            'monthlyTrends',
            'monthlyUsers',
            'hospitalStats',
            'startDate',
            'endDate'
        ));
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'users');
        $format = $request->get('format', 'excel');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $filename = $type . '_report_' . date('Y-m-d_H-i-s');

        switch ($type) {
            case 'users':
                $query = User::with(['healthCard']);
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
                $users = $query->get();
                
                if ($format === 'pdf') {
                    return $this->exportUsersPDF($startDate, $endDate, $filename);
                }
                return Excel::download(new UsersExport($users), $filename . '.xlsx');
                
            case 'hospitals':
                $query = Hospital::with(['services']);
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
                $hospitals = $query->get();
                
                if ($format === 'pdf') {
                    return $this->exportHospitalsPDF($startDate, $endDate, $filename);
                }
                return Excel::download(new HospitalsExport($hospitals), $filename . '.xlsx');
                
            case 'staff':
                $query = \App\Models\Staff::with(['hospital']);
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
                $staff = $query->get();
                
                return Excel::download(new StaffExport($staff), $filename . '.xlsx');
                
            case 'availments':
                $query = PatientAvailment::with(['user', 'hospital', 'service']);
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
                $availments = $query->get();
                
                if ($format === 'pdf') {
                    return $this->exportAvailmentsPDF($startDate, $endDate, $filename);
                }
                return Excel::download(new AvailmentsExport($availments), $filename . '.xlsx');
                
            default:
                return redirect()->back()->with('error', 'Invalid report type.');
        }
    }

    private function exportUsersPDF($startDate, $endDate, $filename)
    {
        $query = User::with(['healthCard']);
        
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        $users = $query->get();
        
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.reports.pdf.users', compact('users', 'startDate', 'endDate'));
        
        return $pdf->download($filename . '.pdf');
    }

    private function exportHospitalsPDF($startDate, $endDate, $filename)
    {
        $query = Hospital::with(['services']);
        
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        $hospitals = $query->get();
        
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.reports.pdf.hospitals', compact('hospitals', 'startDate', 'endDate'));
        
        return $pdf->download($filename . '.pdf');
    }

    private function exportAvailmentsPDF($startDate, $endDate, $filename)
    {
        $query = PatientAvailment::with(['user', 'hospital', 'service']);
        
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        $availments = $query->get();
        
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.reports.pdf.availments', compact('availments', 'startDate', 'endDate'));
        
        return $pdf->download($filename . '.pdf');
    }
}