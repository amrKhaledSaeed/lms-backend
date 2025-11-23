<?php

namespace App\Multitenancy\Tasks;

use App\Models\Tenant;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

class SwitchTenantDatabaseTask implements SwitchTenantTask
{
    /**
     * Make the given tenant current.
     */
    public function makeCurrent(Tenant $tenant): void
    {
        // Only switch database if tenant uses multi-database mode
        if ($tenant->isMultiDatabase()) {
            $this->switchToMultiDatabase($tenant);
        } else {
            $this->switchToSingleDatabase($tenant);
        }
    }

    /**
     * Forget the current tenant.
     */
    public function forgetCurrent(): void
    {
        // Purge the tenant connection
        $tenantConnectionName = $this->getTenantConnectionName();
        
        if ($tenantConnectionName) {
            DB::purge($tenantConnectionName);
        }

        // Reset to default connection
        Config::set('database.default', $this->getOriginalConnectionName());
    }

    /**
     * Switch to multi-database configuration.
     */
    protected function switchToMultiDatabase(Tenant $tenant): void
    {
        $tenantConnectionName = $this->getTenantConnectionName();
        
        // Set the tenant connection configuration
        Config::set("database.connections.{$tenantConnectionName}", $tenant->getDatabaseConfig());
        
        // Purge the old connection
        DB::purge($tenantConnectionName);
        
        // Set tenant connection as default
        Config::set('database.default', $tenantConnectionName);
        
        // Reconnect
        DB::reconnect($tenantConnectionName);
    }

    /**
     * Switch to single database configuration.
     * In single database mode, we stay on the main database but add tenant context.
     */
    protected function switchToSingleDatabase(Tenant $tenant): void
    {
        // In single database mode, we don't switch connections
        // The application should use global scopes or manual tenant filtering
        // Keep the default connection active
        $defaultConnection = $this->getOriginalConnectionName();
        Config::set('database.default', $defaultConnection);
        
        // You can store tenant ID in a context that models can access
        // This is handled by Spatie's currentTenant binding
    }

    /**
     * Get the tenant connection name.
     */
    protected function getTenantConnectionName(): string
    {
        return config('multitenancy.tenant_database_connection_name', 'tenant');
    }

    /**
     * Get the original/landlord connection name.
     */
    protected function getOriginalConnectionName(): string
    {
        return config('multitenancy.landlord_database_connection_name') 
            ?? config('database.default');
    }
}

