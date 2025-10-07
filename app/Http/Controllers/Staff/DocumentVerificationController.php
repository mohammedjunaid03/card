<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hospital;
use Illuminate\Http\Request;

class DocumentVerificationController extends Controller
{
    public function index()
    {
        $pendingUsers = User::where('status', 'pending')
            ->whereNotNull('aadhaar_path')
            ->latest()
            ->paginate(20);

        $pendingHospitals = Hospital::where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('staff.verify-documents', compact('pendingUsers', 'pendingHospitals'));
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:user,hospital',
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string',
        ]);

        if ($request->type === 'user') {
            $user = User::findOrFail($id);
            $user->update([
                'status' => $request->action === 'approve' ? 'active' : 'rejected',
                'verification_notes' => $request->notes,
            ]);
        } else {
            $hospital = Hospital::findOrFail($id);
            $hospital->update([
                'status' => $request->action === 'approve' ? 'approved' : 'rejected',
                'verification_notes' => $request->notes,
            ]);
        }

        return redirect()->back()->with('success', 
            ucfirst($request->type) . ' ' . $request->action . 'd successfully.');
    }
}
