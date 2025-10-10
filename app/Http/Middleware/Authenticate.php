<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // Determine guard and redirect to correct login page
        $guard = $request->route()?->middleware(); // optional, fallback if needed

        // Or you can detect based on the URL prefix
        if ($request->is('hospital/*')) {
            return route('hospital.login'); // create this route if not exists
        }
        if ($request->is('staff/*')) {
            return route('staff.login'); // create this route
        }
        if ($request->is('admin/*')) {
            return route('admin.login'); // create this route
        }

        return route('login'); // default user login
    }
}
