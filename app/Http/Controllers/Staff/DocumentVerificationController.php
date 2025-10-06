<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hospital;

class DocumentVerificationController extends Controller
{
    public function index()
    {
        // Get pending users
        $pendingUsers = User::where('status', 'pending')->get();

        // Get pending hospitals
        $pendingHospitals = Hospital::where('status', 'pending')->get();

        return view('staff.verify-documents', compact('pendingUsers', 'pendingHospitals'));
    }

    public function verify(Request $request, $id)
    {
        $type = $request->input('type'); // 'user' or 'hospital'
        $action = $request->input('action'); // 'approve' or 'reject'

        if ($type === 'user') {
            $user = User::findOrFail($id);
            $user->update([
                'status' => $action === 'approve' ? 'active' : 'rejected'
            ]);
        } elseif ($type === 'hospital') {
            $hospital = Hospital::findOrFail($id);
            $hospital->update([
                'status' => $action === 'approve' ? 'active' : 'rejected'
            ]);
        }

        return redirect()->back()->with('success', ucfirst($type) . ' ' . $action . 'd successfully!');
    }
}
