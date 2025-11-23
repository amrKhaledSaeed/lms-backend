<?php

/**
 * Laravel Multitenancy - Tinker Examples
 * 
 * Run these commands in `php artisan tinker`
 * Copy and paste the examples below
 */

// ============================================
// 1. CREATE SINGLE-DATABASE TENANT
// ============================================

use App\Models\Tenant;
use App\Services\TenantDatabaseService;
use App\Enums\DatabaseType;

$tenant1 = Tenant::create([
    'name' => 'Single DB Corp',
    'domain' => 'singledb.myapp.test',
    'database_type' => DatabaseType::SINGLE, // Using enum
]);

echo "Created tenant: {$tenant1->name} (ID: {$tenant1->id})\n";
echo "Type: {$tenant1->database_type->label()}\n";

// ============================================
// 2. CREATE MULTI-DATABASE TENANT
// ============================================

$tenant2 = Tenant::create([
    'name' => 'Multi DB Corp',
    'domain' => 'multidb.myapp.test',
    'database_type' => DatabaseType::MULTI, // Using enum
    'database_name' => 'lms_tenant_multidb',
    'database_username' => 'root',
    'database_password' => '', // Set your password
]);

echo "Created tenant: {$tenant2->name} (ID: {$tenant2->id})\n";
echo "Type: {$tenant2->database_type->label()}\n";

// ============================================
// 3. AUTO-GENERATE DATABASE NAME
// ============================================

$service = app(TenantDatabaseService::class);
$dbName = $service->generateDatabaseName('Awesome Company');

$tenant3 = Tenant::create([
    'name' => 'Awesome Company',
    'domain' => 'awesome.myapp.test',
    'database_type' => DatabaseType::MULTI,
    'database_name' => $dbName,
    'database_username' => 'root',
    'database_password' => '',
]);

echo "Created tenant with DB: {$tenant3->database_name}\n";

// ============================================
// 4. RUN MIGRATIONS FOR TENANT
// ============================================

$tenant = Tenant::find(2); // Get multi-database tenant
$service = app(TenantDatabaseService::class);
$service->runMigrations($tenant);

echo "Migrations completed for {$tenant->name}\n";

// ============================================
// 5. SEED TENANT DATA
// ============================================

$service->runSeeders($tenant, 'TenantSeeder');
echo "Seeding completed for {$tenant->name}\n";

// ============================================
// 6. MAKE TENANT CURRENT AND QUERY DATA
// ============================================

$tenant = Tenant::find(2);
$tenant->makeCurrent();

// Check current tenant
$current = Tenant::current();
echo "Current tenant: {$current->name}\n";

// Query tenant data
$posts = DB::table('posts')->get();
echo "Posts count: {$posts->count()}\n";

// Check database connection
echo "Current connection: " . config('database.default') . "\n";

// Forget tenant
$tenant->forgetCurrent();

// ============================================
// 7. LIST ALL TENANTS
// ============================================

// All tenants
$allTenants = Tenant::all();
foreach ($allTenants as $t) {
    echo "{$t->id}: {$t->name} ({$t->domain}) - {$t->database_type->label()}\n";
}

// Only multi-database tenants
$multiTenants = Tenant::where('database_type', DatabaseType::MULTI)->get();
echo "\nMulti-database tenants: {$multiTenants->count()}\n";

// Only single-database tenants
$singleTenants = Tenant::where('database_type', DatabaseType::SINGLE)->get();
echo "Single-database tenants: {$singleTenants->count()}\n";

// ============================================
// 8. CHECK TENANT DATABASE TYPE
// ============================================

$tenant = Tenant::find(1);

if ($tenant->isMultiDatabase()) {
    echo "{$tenant->name} uses separate database: {$tenant->database_name}\n";
} else {
    echo "{$tenant->name} uses shared database\n";
}

// ============================================
// 9. GET TENANT DATABASE CONFIG
// ============================================

$tenant = Tenant::find(2);
$config = $tenant->getDatabaseConfig();
print_r($config);

// ============================================
// 10. EXECUTE CODE IN TENANT CONTEXT
// ============================================

$tenant = Tenant::find(2);

$tenant->makeCurrent();

// Do tenant-specific operations
$postCount = DB::table('posts')->count();
echo "Tenant {$tenant->name} has {$postCount} posts\n";

$tenant->forgetCurrent();

// ============================================
// 11. BATCH OPERATIONS ON ALL TENANTS
// ============================================

// Run migrations for all multi-database tenants
$service = app(TenantDatabaseService::class);

Tenant::where('database_type', DatabaseType::MULTI)->each(function ($tenant) use ($service) {
    echo "Processing tenant: {$tenant->name}\n";
    $service->runMigrations($tenant);
    echo "✓ Migrations completed\n";
});

// ============================================
// 12. DELETE TENANT (WITH DATABASE)
// ============================================

$tenant = Tenant::find(3);
$service = app(TenantDatabaseService::class);

// Delete tenant and drop its database
$service->deleteTenant($tenant, dropDatabase: true);

echo "Tenant and database deleted\n";

// ============================================
// 13. CHECK IF DATABASE EXISTS
// ============================================

$service = app(TenantDatabaseService::class);
$exists = $service->databaseExists('lms_tenant_multidb');

echo $exists ? "Database exists\n" : "Database does not exist\n";

// ============================================
// 14. CREATE MULTIPLE TENANTS AT ONCE
// ============================================

$tenantsData = [
    [
        'name' => 'Company A',
        'domain' => 'companya.myapp.test',
        'database_type' => DatabaseType::SINGLE,
    ],
    [
        'name' => 'Company B',
        'domain' => 'companyb.myapp.test',
        'database_type' => DatabaseType::MULTI,
        'database_name' => 'lms_tenant_companyb',
        'database_username' => 'root',
        'database_password' => '',
    ],
    [
        'name' => 'Company C',
        'domain' => 'companyc.myapp.test',
        'database_type' => DatabaseType::MULTI,
        'database_name' => 'lms_tenant_companyc',
        'database_username' => 'root',
        'database_password' => '',
    ],
];

foreach ($tenantsData as $data) {
    $tenant = Tenant::create($data);
    echo "✓ Created: {$tenant->name}\n";
}

// ============================================
// 15. UPDATE TENANT
// ============================================

$tenant = Tenant::find(1);
$tenant->update([
    'name' => 'Updated Company Name',
]);

echo "Tenant updated: {$tenant->name}\n";

// ============================================
// 16. SWITCH TENANT DATABASE TYPE
// ============================================

// WARNING: Be careful when switching modes!
// This doesn't migrate data automatically

$tenant = Tenant::find(1);

// Change from single to multi
$tenant->update([
    'database_type' => DatabaseType::MULTI,
    'database_name' => 'lms_tenant_new',
    'database_username' => 'root',
    'database_password' => '',
]);

// The database will be created, but you need to migrate and seed
$service = app(TenantDatabaseService::class);
$service->runMigrations($tenant);

echo "Tenant switched to multi-database mode\n";

// ============================================
// 17. GET TENANT BY DOMAIN
// ============================================

$tenant = Tenant::where('domain', 'multidb.myapp.test')->first();

if ($tenant) {
    echo "Found tenant: {$tenant->name}\n";
} else {
    echo "Tenant not found\n";
}

// ============================================
// 18. COUNT TENANTS BY TYPE
// ============================================

$singleCount = Tenant::where('database_type', DatabaseType::SINGLE)->count();
$multiCount = Tenant::where('database_type', DatabaseType::MULTI)->count();
$totalCount = Tenant::count();

echo "Total tenants: {$totalCount}\n";
echo "Single-DB: {$singleCount}\n";
echo "Multi-DB: {$multiCount}\n";

// Using enum helper
echo "\nAvailable types:\n";
foreach (DatabaseType::toArray() as $type) {
    echo "- {$type['label']} (value: {$type['value']})\n";
}

// ============================================
// 19. WORKING WITH TENANT RELATIONSHIPS
// ============================================

// If you have user relationships, etc.
$tenant = Tenant::find(1);
$tenant->makeCurrent();

// Create user in tenant context
$user = \App\Models\User::create([
    'name' => 'Tenant Admin',
    'email' => 'admin@tenant.test',
    'password' => bcrypt('password'),
]);

echo "Created user: {$user->name}\n";

$tenant->forgetCurrent();

// ============================================
// 20. DEBUGGING - CHECK CURRENT STATE
// ============================================

echo "Current tenant: " . (Tenant::current() ? Tenant::current()->name : 'None') . "\n";
echo "Database connection: " . config('database.default') . "\n";
echo "Database name: " . config('database.connections.' . config('database.default') . '.database') . "\n";

