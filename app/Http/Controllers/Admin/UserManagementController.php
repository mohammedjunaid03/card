<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\EncryptionService;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('healthCard');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q->where('name', 'like', "%{$search}%")
                                       ->orWhere('email', 'like', "%{$search}%")
                                       ->orWhere('mobile', 'like', "%{$search}%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::with(['healthCard', 'availments.hospital'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:active,blocked']);

        $user = User::findOrFail($id);
        $user->update(['status' => $request->status]);

        return back()->with('success', 'User status updated successfully');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }

    public function downloadAadhaar($id, EncryptionService $encryptionService)
    {
        $user = User::findOrFail($id);
        $decryptedContent = $encryptionService->decryptFileContent($user->aadhaar_path);
        $extension = pathinfo($user->aadhaar_path, PATHINFO_EXTENSION);

        return response($decryptedContent)
            ->header('Content-Type', 'application/' . $extension)
            ->header('Content-Disposition', 'attachment; filename="aadhaar_' . $user->id . '.' . $extension . '"');
    }
}
