<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Process the request first
        $response = $next($request);
        
        // 2. Log important write actions (POST, PUT, PATCH, DELETE) only if the response was successful (2xx status).
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE']) && $response->isSuccessful()) {
            $this->logAction($request);
        }
        
        return $response;
    }
    
    /**
     * Log the action details.
     */
    private function logAction($request)
    {
        $user = $this->getAuthenticatedUser();
        
        // Exclude sensitive fields and internal Laravel tokens from the payload
        $newValues = $request->except([
            '_token', 
            '_method', 
            'password', 
            'password_confirmation', 
            'current_password'
        ]);
        
        if ($user) {
            AuditLog::create([
                // Use model name (e.g., 'User') for polymorphic relationship
                'user_type' => $user['type'], 
                'user_id' => $user['id'],
                // Log the action path or route name
                'action' => $request->route() ? $request->route()->getName() : $request->path(),
                // Log the request method as a model identifier for generic updates
                'model_type' => $request->method(), 
                // Log the request payload as the 'new_values'
                'new_values' => $newValues, 
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }
    }
    
    /**
     * Get the currently authenticated user details across all guards.
     */
    private function getAuthenticatedUser()
    {
        // The 'type' must be the fully qualified class name or the model name (conventionally capitalized)
        if (Auth::guard('web')->check()) {
            return ['type' => 'User', 'id' => Auth::guard('web')->id()];
        } elseif (Auth::guard('hospital')->check()) {
            return ['type' => 'Hospital', 'id' => Auth::guard('hospital')->id()];
        } elseif (Auth::guard('staff')->check()) {
            return ['type' => 'Staff', 'id' => Auth::guard('staff')->id()];
        } elseif (Auth::guard('admin')->check()) {
            return ['type' => 'Admin', 'id' => Auth::guard('admin')->id()];
        }
        
        return null;
    }
}