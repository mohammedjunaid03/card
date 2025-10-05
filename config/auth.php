<?php

return [

    // config/auth.php - Guards section (Conceptual)
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users', // Uses App\Models\User
    ],
    'hospital' => [
        'driver' => 'session',
        'provider' => 'hospitals', // Uses App\Models\Hospital
    ],
    'staff' => [
        'driver' => 'session',
        'provider' => 'staff', // Uses App\Models\Staff
    ],
    'admin' => [
        'driver' => 'session',
        'provider' => 'admins', // Uses App\Models\Admin
    ],
],

// config/auth.php - Providers section (Conceptual)
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
    'hospitals' => [
        'driver' => 'eloquent',
        'model' => App\Models\Hospital::class,
    ],
    'staff' => [
        'driver' => 'eloquent',
        'model' => App\Models\Staff::class,
    ],
    'admins' => [
        'driver' => 'eloquent',
        'model' => App\Models\Admin::class,
    ],
],

];