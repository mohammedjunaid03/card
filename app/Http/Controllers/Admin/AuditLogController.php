<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::latest();

        // User type filter
        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
        }

        // Action type filter
        if ($request->filled('action_type')) {
            $query->where('action', 'like', $request->action_type . '%');
        }

        // Date range filters
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('user_type', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(25);

        return view('admin.audit-logs', compact('logs'));
    }

    public function export(Request $request)
    {
        $query = AuditLog::latest();

        // Apply same filters as index
        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
        }

        if ($request->filled('action_type')) {
            $query->where('action', 'like', $request->action_type . '%');
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('user_type', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        $logs = $query->limit(10000)->get();

        $filename = 'audit_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Timestamp',
                'User Type',
                'User ID',
                'Action',
                'IP Address',
                'User Agent'
            ]);

            // CSV data
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user_type,
                    $log->user_id,
                    $log->action,
                    $log->ip_address,
                    $log->user_agent
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function clear(Request $request)
    {
        $deleted = AuditLog::where('created_at', '<', now()->subDays(30))->delete();
        
        return redirect()->back()->with('success', "Cleared {$deleted} old audit log entries.");
    }
}
