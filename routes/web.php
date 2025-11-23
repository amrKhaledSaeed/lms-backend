<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response()->json([
        'message' => 'LMS Backend',
        'api_prefix' => '/api',
        'documentation' => [
            'landlord_routes' => '/api/landlord',
            'tenant_routes' => '/api/tenant (requires tenant domain)',
        ],
        'health_check' => '/api/health',
    ]);
});
