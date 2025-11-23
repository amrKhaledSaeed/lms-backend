# 🚀 Laravel Multitenancy - Quick Start Guide

## Installation Complete! ✅

Your Laravel 12 application is now configured with Spatie Multitenancy supporting both **single-database** and **multi-database** modes.

---

## 📦 What Was Installed

```bash
composer require spatie/laravel-multitenancy
```

**Version:** spatie/laravel-multitenancy v4.0.7

---

## 🏗️ Project Structure

```
app/
├── Models/
│   └── Tenant.php                          # Custom tenant model
├── Multitenancy/
│   ├── Tasks/
│   │   └── SwitchTenantDatabaseTask.php   # Database switching logic
│   └── TenantFinder/
│       └── DomainTenantFinder.php         # Domain-based tenant finder
├── Services/
│   └── TenantDatabaseService.php          # Tenant management service
└── Http/
    ├── Middleware/
    │   └── InitializeTenancyByDomain.php  # Tenant identification middleware
    └── Controllers/
        └── TenantDashboardController.php   # Example controller

config/
└── multitenancy.php                        # Multitenancy configuration

database/
├── migrations/
│   ├── landlord/
│   │   └── 2025_11_23_112523_create_landlord_tenants_table.php
│   └── tenant/
│       └── 2025_11_23_000001_create_tenant_posts_table.php
└── seeders/
    └── TenantSeeder.php                    # Example tenant seeder
```

---

## ⚡ Quick Commands

### 1. Create a Single-Database Tenant

```bash
php artisan tinker
```

```php
Tenant::create([
    'name' => 'Acme Corp',
    'domain' => 'acme.myapp.test',
    'database_type' => 'single',
]);
```

### 2. Create a Multi-Database Tenant

```php
Tenant::create([
    'name' => 'TechStart Inc',
    'domain' => 'techstart.myapp.test',
    'database_type' => 'multi',
    'database_name' => 'lms_tenant_techstart',
    'database_username' => 'root',
    'database_password' => 'password',
]);
```

### 3. Run Tenant Migrations

```php
use App\Services\TenantDatabaseService;

$tenant = Tenant::find(1);
$service = app(TenantDatabaseService::class);
$service->runMigrations($tenant);
```

### 4. Seed Tenant Data

```php
$service->runSeeders($tenant, 'TenantSeeder');
```

---

## 🌐 Testing

### Update Your Hosts File

**Windows:** `C:\Windows\System32\drivers\etc\hosts`

```
127.0.0.1 acme.myapp.test
127.0.0.1 techstart.myapp.test
```

### Start Server

```bash
php artisan serve
```

### Test Routes

- **Landlord:** http://localhost:8000/
- **List Tenants:** http://localhost:8000/api/tenants
- **Tenant Dashboard:** http://acme.myapp.test:8000/dashboard
- **Tenant API:** http://acme.myapp.test:8000/api/tenant/info

---

## 📝 Example Usage

```php
use App\Models\Tenant;
use App\Services\TenantDatabaseService;

// Create service instance
$service = app(TenantDatabaseService::class);

// Create tenant with auto-generated database name
$dbName = $service->generateDatabaseName('My Company');

$tenant = Tenant::create([
    'name' => 'My Company',
    'domain' => 'mycompany.myapp.test',
    'database_type' => 'multi',
    'database_name' => $dbName,
    'database_username' => 'root',
    'database_password' => '',
]);

// Run migrations for this tenant
$service->runMigrations($tenant);

// Seed data
$service->runSeeders($tenant);

// Make tenant current
$tenant->makeCurrent();

// Check current tenant
Tenant::current();

// Forget tenant
$tenant->forgetCurrent();
```

---

## 🔑 Key Features

✅ **Automatic tenant identification** by domain  
✅ **Flexible database modes** (single/multi)  
✅ **Auto-database creation** for multi-database tenants  
✅ **Middleware-based tenant switching**  
✅ **Tenant-aware caching**  
✅ **Migration and seeding support**  
✅ **Service class for tenant management**  

---

## 📚 Documentation

For detailed documentation, see **MULTITENANCY_SETUP.md**

---

## 🎯 Next Steps

1. ✅ Update your hosts file with tenant domains
2. ✅ Create your first tenant using tinker
3. ✅ Run tenant migrations
4. ✅ Test the tenant dashboard
5. ✅ Build your tenant-specific features

**Happy coding! 🚀**

