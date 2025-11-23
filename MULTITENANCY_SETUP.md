# Laravel 12 Multi-Tenancy Setup Guide

## 🎯 Overview

This Laravel application uses **Spatie Laravel Multitenancy** (v4.0.7) with support for:

✅ **Single Database Tenancy** - All tenants share the main database  
✅ **Multi-Database Tenancy** - Each tenant has a separate database  

The mode is controlled by the `database_type` field in the `tenants` table (`single` or `multi`).

---

## 📦 Installation

### 1. Install Package
```bash
composer require spatie/laravel-multitenancy
```

### 2. Run Migrations
```bash
php artisan migrate
```

This creates the `tenants` table with fields:
- `id` - Tenant ID
- `name` - Tenant name
- `domain` - Tenant domain (unique)
- `database_type` - `single` or `multi`
- `database_name` - Database name (for multi mode)
- `database_username` - DB username (for multi mode)
- `database_password` - DB password (for multi mode)

---

## 🛠️ Configuration

### Config File: `config/multitenancy.php`

Key configurations:
- **tenant_finder**: `DomainTenantFinder` - Identifies tenants by domain
- **tenant_model**: `App\Models\Tenant` - Custom tenant model
- **switch_tenant_tasks**: Custom database switching logic
- **tenant_database_connection_name**: `tenant` - Connection for multi-database tenants
- **landlord_database_connection_name**: `mysql` - Main database connection

---

## 🏗️ Architecture

### 1. Tenant Model (`app/Models/Tenant.php`)
- Extends `Spatie\Multitenancy\Models\Tenant`
- Methods:
  - `isMultiDatabase()` - Check if tenant uses separate database
  - `isSingleDatabase()` - Check if tenant uses shared database
  - `getDatabaseConfig()` - Get database configuration
  - `createDatabase()` - Auto-create database (multi mode)
  - `dropDatabase()` - Drop tenant database

### 2. Database Switching Task (`app/Multitenancy/Tasks/SwitchTenantDatabaseTask.php`)
- Switches to tenant's database if `database_type = 'multi'`
- Stays on main database if `database_type = 'single'`

### 3. Tenant Finder (`app/Multitenancy/TenantFinder/DomainTenantFinder.php`)
- Identifies tenants by request domain
- Automatically applied via middleware

### 4. Middleware (`app/Http/Middleware/InitializeTenancyByDomain.php`)
- Automatically identifies and switches to tenant context
- Applied globally to web routes

---

## 🚀 Usage

### Creating Tenants

#### Method 1: Using Tinker

```bash
php artisan tinker
```

**Create Single-Database Tenant:**
```php
use App\Models\Tenant;

$tenant = Tenant::create([
    'name' => 'Acme Corp',
    'domain' => 'acme.myapp.test',
    'database_type' => 'single',
]);
```

**Create Multi-Database Tenant:**
```php
use App\Models\Tenant;

$tenant = Tenant::create([
    'name' => 'TechStart Inc',
    'domain' => 'techstart.myapp.test',
    'database_type' => 'multi',
    'database_name' => 'lms_tenant_techstart',
    'database_username' => 'root', // Use your DB credentials
    'database_password' => 'password',
]);
```

#### Method 2: Using Service Class

```php
use App\Services\TenantDatabaseService;

$service = app(TenantDatabaseService::class);

// Single database tenant
$tenant1 = $service->createTenant([
    'name' => 'Company A',
    'domain' => 'companya.myapp.test',
    'database_type' => 'single',
]);

// Multi database tenant
$tenant2 = $service->createTenant([
    'name' => 'Company B',
    'domain' => 'companyb.myapp.test',
    'database_type' => 'multi',
    'database_name' => 'lms_tenant_companyb',
    'database_username' => 'root',
    'database_password' => 'password',
]);
```

#### Method 3: Auto-Generate Database Name

```php
use App\Services\TenantDatabaseService;

$service = app(TenantDatabaseService::class);

$dbName = $service->generateDatabaseName('My Awesome Tenant');
// Returns: laravel_tenant_my-awesome-tenant

$tenant = Tenant::create([
    'name' => 'My Awesome Tenant',
    'domain' => 'awesome.myapp.test',
    'database_type' => 'multi',
    'database_name' => $dbName,
    'database_username' => 'root',
    'database_password' => 'password',
]);
```

---

## 🗄️ Running Migrations for Tenants

### For Multi-Database Tenants

Tenant-specific migrations are stored in `database/migrations/tenant/`.

**Run migrations for a specific tenant:**

```bash
php artisan tinker
```

```php
use App\Models\Tenant;
use App\Services\TenantDatabaseService;

$tenant = Tenant::find(1);
$service = app(TenantDatabaseService::class);
$service->runMigrations($tenant);
```

**Run migrations for all multi-database tenants:**

```php
use App\Models\Tenant;
use App\Services\TenantDatabaseService;

$service = app(TenantDatabaseService::class);

Tenant::where('database_type', 'multi')->each(function ($tenant) use ($service) {
    echo "Running migrations for {$tenant->name}...\n";
    $service->runMigrations($tenant);
});
```

---

## 🌱 Seeding Tenant Data

### Create a Tenant Seeder

```bash
php artisan make:seeder TenantSeeder
```

**database/seeders/TenantSeeder.php:**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('posts')->insert([
            'title' => 'Welcome to Your Tenant',
            'content' => 'This is your first post!',
            'published' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
```

### Run Seeder for a Tenant

```php
use App\Models\Tenant;
use App\Services\TenantDatabaseService;

$tenant = Tenant::find(1);
$service = app(TenantDatabaseService::class);
$service->runSeeders($tenant, 'TenantSeeder');
```

---

## 🧪 Testing Routes

### 1. Landlord Routes (No Tenant)

**Main App Route:**
```
http://localhost:8000/
```

**List All Tenants:**
```
http://localhost:8000/api/tenants
```

### 2. Tenant Routes (Tenant-Specific)

**Tenant Dashboard:**
```
http://acme.myapp.test/dashboard
```

**Tenant Info API:**
```
http://acme.myapp.test/api/tenant/info
```

---

## 🔧 Local Development Setup

### 1. Configure Hosts File

Add tenant domains to your hosts file:

**Windows:** `C:\Windows\System32\drivers\etc\hosts`  
**Mac/Linux:** `/etc/hosts`

```
127.0.0.1 acme.myapp.test
127.0.0.1 techstart.myapp.test
127.0.0.1 companya.myapp.test
127.0.0.1 companyb.myapp.test
```

### 2. Start Development Server

```bash
php artisan serve
```

### 3. Access Tenants

- Landlord: http://localhost:8000/
- Tenant 1: http://acme.myapp.test:8000/dashboard
- Tenant 2: http://techstart.myapp.test:8000/dashboard

---

## 📝 Complete Tinker Examples

### Create and Setup Multi-Database Tenant

```php
use App\Models\Tenant;
use App\Services\TenantDatabaseService;

// 1. Create tenant
$tenant = Tenant::create([
    'name' => 'Demo Corp',
    'domain' => 'demo.myapp.test',
    'database_type' => 'multi',
    'database_name' => 'lms_tenant_demo',
    'database_username' => 'root',
    'database_password' => '',
]);

// 2. Database is auto-created, now run migrations
$service = app(TenantDatabaseService::class);
$service->runMigrations($tenant);

// 3. Run seeders (optional)
$service->runSeeders($tenant);

// 4. Make tenant current and interact
$tenant->makeCurrent();

// 5. Check current tenant
Tenant::current(); // Returns current tenant

// 6. Query tenant's data
DB::table('posts')->get();

// 7. Forget current tenant
$tenant->forgetCurrent();
```

### Create and Setup Single-Database Tenant

```php
use App\Models\Tenant;

// 1. Create tenant (no migrations needed)
$tenant = Tenant::create([
    'name' => 'Shared Corp',
    'domain' => 'shared.myapp.test',
    'database_type' => 'single',
]);

// 2. Make current
$tenant->makeCurrent();

// 3. All data is in the same database
// Use global scopes or manual filtering for tenant data isolation

// 4. Forget current
$tenant->forgetCurrent();
```

### List All Tenants

```php
use App\Models\Tenant;

// Get all tenants
Tenant::all();

// Get only multi-database tenants
Tenant::where('database_type', 'multi')->get();

// Get only single-database tenants
Tenant::where('database_type', 'single')->get();
```

### Delete Tenant and Database

```php
use App\Models\Tenant;
use App\Services\TenantDatabaseService;

$tenant = Tenant::find(1);
$service = app(TenantDatabaseService::class);

// Delete tenant and drop database
$service->deleteTenant($tenant, dropDatabase: true);
```

---

## 🔐 Security Notes

1. **Database Credentials:** For multi-database tenants, consider encrypting credentials in the database
2. **Database User Creation:** Auto-creating database users is disabled by default. Enable in `Tenant::createDatabase()` if needed
3. **Database Deletion:** Auto-dropping databases on tenant deletion is disabled. Enable cautiously

---

## 📊 Database Structure

### Single Database Mode
```
main_database
├── tenants (landlord table)
├── users (shared or tenant-aware with global scope)
├── posts (tenant-aware with global scope)
└── ... (other tables)
```

### Multi-Database Mode
```
main_database
└── tenants (landlord table)

lms_tenant_acme
├── users
├── posts
└── ... (tenant-specific tables)

lms_tenant_techstart
├── users
├── posts
└── ... (tenant-specific tables)
```

---

## 🎓 Best Practices

1. **Always check tenant context** before running tenant-specific operations
2. **Use `Tenant::current()`** to get the current tenant in controllers
3. **Separate migrations:** Keep landlord and tenant migrations separate
4. **Test both modes** during development
5. **Use queues carefully:** Ensure jobs are tenant-aware
6. **Cache:** Use tenant-prefixed cache keys (handled by `PrefixCacheTask`)

---

## 🐛 Troubleshooting

### Issue: Tenant not found
- Check if domain is correctly set in database
- Verify hosts file configuration
- Ensure middleware is registered

### Issue: Database connection error (multi mode)
- Verify database credentials in tenant record
- Ensure database exists
- Check MySQL user permissions

### Issue: Wrong data showing
- Check which tenant is current: `Tenant::current()`
- Verify database connection: `config('database.default')`
- Ensure proper tenant switching logic

---

## 📚 Resources

- [Spatie Multitenancy Docs](https://spatie.be/docs/laravel-multitenancy/v4/introduction)
- [Laravel 12 Documentation](https://laravel.com/docs/12.x)

---

## ✅ Setup Checklist

- [x] Package installed
- [x] Migrations published and run
- [x] Tenant model created
- [x] Custom database switching task implemented
- [x] Tenant finder configured
- [x] Middleware registered
- [x] Routes configured
- [x] Service class created
- [x] Documentation complete

**Your multi-tenancy setup is ready! 🚀**

