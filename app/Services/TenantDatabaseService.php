<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class TenantDatabaseService
{
    /**
     * Create a new tenant with optional database creation.
     */
    public function createTenant(array $data): Tenant
    {
        $tenant = Tenant::create($data);

        // Database is auto-created by model's created event
        // But we can run migrations here if needed
        if ($tenant->isMultiDatabase()) {
            $this->runMigrations($tenant);
        }

        return $tenant;
    }

    /**
     * Run migrations for a specific tenant.
     */
    public function runMigrations(Tenant $tenant): void
    {
        if ($tenant->isMultiDatabase()) {
            // Make tenant current to switch to its database
            $tenant->makeCurrent();

            // Run migrations
            Artisan::call('migrate', [
                '--database' => 'tenant',
                '--path' => 'database/migrations/tenant',
                '--force' => true,
            ]);

            // Forget current tenant
            $tenant->forgetCurrent();
        }
    }

    /**
     * Run seeders for a specific tenant.
     */
    public function runSeeders(Tenant $tenant, string $seeder = 'DatabaseSeeder'): void
    {
        $tenant->makeCurrent();

        Artisan::call('db:seed', [
            '--class' => $seeder,
            '--database' => $tenant->isMultiDatabase() ? 'tenant' : 'mysql',
            '--force' => true,
        ]);

        $tenant->forgetCurrent();
    }

    /**
     * Check if a database exists.
     */
    public function databaseExists(string $databaseName): bool
    {
        try {
            $databases = DB::select('SHOW DATABASES');
            foreach ($databases as $database) {
                if ($database->Database === $databaseName) {
                    return true;
                }
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Generate a unique database name for a tenant.
     */
    public function generateDatabaseName(string $tenantName): string
    {
        $slug = \Illuminate\Support\Str::slug($tenantName);
        $baseName = config('database.connections.mysql.database') ?? 'laravel';
        
        return "{$baseName}_tenant_{$slug}";
    }

    /**
     * Delete a tenant and optionally drop its database.
     */
    public function deleteTenant(Tenant $tenant, bool $dropDatabase = false): void
    {
        if ($dropDatabase && $tenant->isMultiDatabase()) {
            $tenant->dropDatabase();
        }

        $tenant->delete();
    }
}

