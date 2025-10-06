<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the hospital dashboard.
     */
    public function index()
    {
        return view('hospital.dashboard');
    }
}