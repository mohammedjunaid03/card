<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PatientAvailment;
use Illuminate\Support\Facades\Auth;

class DiscountHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = PatientAvailment::with(['hospital', 'service'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        // Filter by date range if provided
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Filter by hospital if provided
        if ($request->filled('hospital_id')) {
            $query->where('hospital_id', $request->hospital_id);
        }

        $availments = $query->paginate(15);
        
        // Calculate total savings
        $totalSavings = PatientAvailment::where('user_id', $user->id)
            ->sum('discount_amount');

        // Get hospitals for filter dropdown
        $hospitals = \App\Models\Hospital::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('user.discount-history', compact('availments', 'totalSavings', 'hospitals'));
    }
}
