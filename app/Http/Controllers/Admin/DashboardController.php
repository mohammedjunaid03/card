<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $reportService;
    
    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }
    
    public function index()
    {
        $stats = $this->reportService->getDashboardStats();
        $trends = $this->reportService->getMonthlyTrends(6);
        
        return view('admin.dashboard', compact('stats', 'trends'));
    }
}