<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AuditLog;

class AuditLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Log the request if user is authenticated
        if (auth()->check() || auth('hospital')->check() || auth('staff')->check() || auth('admin')->check()) {
            $this->logRequest($request, $response);
        }

        return $response;
    }

    /**
     * Log the request details
     */
    private function logRequest(Request $request, Response $response): void
    {
        try {
            $user = null;
            $userType = null;
            $userId = null;

            // Determine which guard is being used
            if (auth()->check()) {
                $user = auth()->user();
                $userType = 'user';
                $userId = $user->id;
            } elseif (auth('hospital')->check()) {
                $user = auth('hospital')->user();
                $userType = 'hospital';
                $userId = $user->id;
            } elseif (auth('staff')->check()) {
                $user = auth('staff')->user();
                $userType = 'staff';
                $userId = $user->id;
            } elseif (auth('admin')->check()) {
                $user = auth('admin')->user();
                $userType = 'admin';
                $userId = $user->id;
            }

            if ($user) {
                AuditLog::create([
                    'user_id' => $userId,
                    'user_type' => $userType,
                    'action' => $request->method() . ' ' . $request->route()->getName(),
                    'description' => $this->getActionDescription($request),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'request_data' => $this->getRequestData($request),
                    'response_status' => $response->getStatusCode(),
                ]);
            }
        } catch (\Exception $e) {
            // Log error but don't break the request
            \Log::error('Audit log error: ' . $e->getMessage());
        }
    }

    /**
     * Get action description based on route
     */
    private function getActionDescription(Request $request): string
    {
        $routeName = $request->route()->getName();
        
        $descriptions = [
            'user.dashboard' => 'Accessed user dashboard',
            'user.profile' => 'Viewed profile',
            'user.profile.update' => 'Updated profile',
            'user.health-card' => 'Viewed health card',
            'user.health-card.download' => 'Downloaded health card',
            'hospital.dashboard' => 'Accessed hospital dashboard',
            'hospital.verify-card' => 'Verified health card',
            'admin.dashboard' => 'Accessed admin dashboard',
            'admin.users.index' => 'Viewed users list',
            'admin.hospitals.index' => 'Viewed hospitals list',
        ];

        return $descriptions[$routeName] ?? 'Performed action: ' . $routeName;
    }

    /**
     * Get sanitized request data
     */
    private function getRequestData(Request $request): ?array
    {
        $data = $request->all();
        
        // Remove sensitive data
        $sensitiveFields = ['password', 'password_confirmation', 'current_password', 'new_password'];
        foreach ($sensitiveFields as $field) {
            unset($data[$field]);
        }

        // Limit data size
        if (strlen(json_encode($data)) > 1000) {
            return ['data_size' => 'large', 'truncated' => true];
        }

        return $data;
    }
}