<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportService;
    
    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }
    
    public function index()
    {
        // View for selecting report type and filters
        // Placeholder for the report view
        return view('admin.reports.index');
    }
    
    /**
     * Export all users data report (CSV, Excel, PDF).
     * This method relies on the ReportService which calls the UsersExport class.
     */
    public function exportUsers(Request $request)
    {
        // Default export format is Excel (.xlsx)
        $format = $request->input('format', 'xlsx'); 
        
        // Allowed formats should be validated (e.g., in a request file, but done here for brevity)
        if (!in_array($format, ['xlsx', 'csv', 'pdf'])) {
            return back()->with('error', 'Invalid export format requested.');
        }

        try {
            // The service handles fetching data and initiating the download
            return $this->reportService->exportUsersReport($format);
        } catch (\Exception $e) {
            // Log the exception and provide a user-friendly error
            \Log::error("Report Export Failed: " . $e->getMessage());
            return back()->with('error', 'Error generating report. Please check server logs.');
        }
    }
    
    // Additional report export methods (e.g., for availments, hospitals) can be added here.
}