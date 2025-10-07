<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HealthCard;
use Illuminate\Http\Request;

class CardApprovalController extends Controller
{
    public function index()
    {
        $pendingCards = HealthCard::where('status', 'pending')->with('user')->get();
        
        return view('admin.card-approvals', compact('pendingCards'));
    }

    public function approve(Request $request, $id)
    {
        $card = HealthCard::findOrFail($id);
        $card->update(['status' => 'active']);
        
        return redirect()->back()->with('success', 'Health card approved successfully.');
    }
}
