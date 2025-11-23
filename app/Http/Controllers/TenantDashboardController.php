<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantDashboardController extends Controller
{
    /**
     * Display the tenant dashboard.
     */
    public function index(Request $request)
    {
        $tenant = Tenant::current();

        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'No tenant found',
                'error' => 'Please access this via a tenant domain',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Welcome to your tenant dashboard!',
            'data' => [
                'tenant' => [
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
                ],
                'database_connection' => config('database.default'),
                'is_multi_database' => $tenant->isMultiDatabase(),
            ],
        ]);
    }
}
