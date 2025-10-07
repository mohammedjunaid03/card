<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HealthCardController extends Controller
{
    public function index()
    {
        return view('user.health-card');
    }

    public function download()
    {
        // Health card download logic
        return response()->download('path/to/health-card.pdf');
    }
}