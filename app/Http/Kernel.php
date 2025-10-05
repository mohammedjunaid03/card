protected $routeMiddleware = [
    // ... existing middleware
    'user' => \App\Http\Middleware\CheckUserRole::class,
    'hospital' => \App\Http\Middleware\CheckHospitalRole::class,
    'staff' => \App\Http\Middleware\CheckStaffRole::class,
    'admin' => \App\Http\Middleware\CheckAdminRole::class,
];