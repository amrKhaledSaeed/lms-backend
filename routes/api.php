<?php

use App\Http\Controllers\TenantDashboardController;
use App\Models\Tenant;
use App\Enums\DatabaseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*
|--------------------------------------------------------------------------
| Landlord API Routes (No Tenant Required)
|--------------------------------------------------------------------------
|
| These routes are for the main application management.
| They don't require tenant context and use the landlord database.
|
*/

Route::prefix('landlord')->group(function () {
    
    // Get application info
    Route::get('/', function () {
        return response()->json([
            'message' => 'LMS Backend API',
            'version' => '1.0.0',
            'app' => config('app.name'),
            'tenants_count' => Tenant::count(),
        ]);
    });

    // List all tenants
    Route::get('/tenants', function () {
        $tenants = Tenant::all()->map(function ($tenant) {
            return [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'domain' => $tenant->domain,
                'database_type' => [
                    'value' => $tenant->database_type->value,
                    'label' => $tenant->database_type->label(),
                    'string_value' => $tenant->database_type->stringValue(),
                ],
                'is_multi_database' => $tenant->isMultiDatabase(),
                'created_at' => $tenant->created_at,
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $tenants,
            'count' => $tenants->count(),
        ]);
    });

    // Get single tenant
    Route::get('/tenants/{id}', function ($id) {
        $tenant = Tenant::find($id);
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'domain' => $tenant->domain,
                'database_type' => [
                    'value' => $tenant->database_type->value,
                    'label' => $tenant->database_type->label(),
                    'string_value' => $tenant->database_type->stringValue(),
                    'description' => $tenant->database_type->description(),
                ],
                'database_name' => $tenant->database_name,
                'is_multi_database' => $tenant->isMultiDatabase(),
                'created_at' => $tenant->created_at,
                'updated_at' => $tenant->updated_at,
            ],
        ]);
    });

    // Get database type options
    Route::get('/database-types', function () {
        return response()->json([
            'success' => true,
            'data' => DatabaseType::toArray(),
        ]);
    });
});

/*
|--------------------------------------------------------------------------
| Tenant API Routes (Tenant-Specific)
|--------------------------------------------------------------------------
|
| These routes require tenant context. The middleware automatically
| identifies the tenant by domain and switches database context.
|
*/

Route::prefix('tenant')->middleware('tenant')->group(function () {
    
    // Get current tenant dashboard
    Route::get('/dashboard', [TenantDashboardController::class, 'index'])
        ->name('api.tenant.dashboard');

    // Get current tenant info
    Route::get('/info', function () {
        $tenant = Tenant::current();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'No tenant context found',
                'error' => 'Please access this API via a tenant domain',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'domain' => $tenant->domain,
                'database_type' => [
                    'value' => $tenant->database_type->value,
                    'label' => $tenant->database_type->label(),
                ],
                'connection' => config('database.default'),
                'is_multi_database' => $tenant->isMultiDatabase(),
            ],
        ]);
    });

    // Get tenant stats (example)
    Route::get('/stats', function () {
        $tenant = Tenant::current();
        
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'No tenant context',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'tenant_id' => $tenant->id,
                'tenant_name' => $tenant->name,
                'database_type' => $tenant->database_type->label(),
                // Add your tenant-specific stats here
                // 'users_count' => User::count(),
                // 'posts_count' => Post::count(),
            ],
        ]);
    });
});

/*
|--------------------------------------------------------------------------
| Health Check (No Authentication)
|--------------------------------------------------------------------------
*/

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
    ]);
});


