# 🏢 Laravel 12 Multi-Tenancy System

## Overview

A complete **Laravel 12** multi-tenancy implementation using **Spatie Laravel Multitenancy v4.0.7** with support for both **single-database** and **multi-database** tenancy modes.

---

## ✨ Features

### Core Features
- ✅ **Dual Tenancy Modes**
  - Single Database: All tenants share one database
  - Multi-Database: Each tenant has separate database
  
- ✅ **Domain-Based Tenant Identification**
  - Automatic tenant detection from request domain
  - Seamless tenant context switching

- ✅ **Database Management**
  - Auto-create databases for multi-database tenants
  - Dynamic database configuration
  - Tenant-specific migrations
  - Tenant-specific seeding

- ✅ **Middleware Integration**
  - Automatic tenant initialization
  - Applied globally to web routes
  - Transparent to application code

- ✅ **Service Layer**
  - TenantDatabaseService for tenant operations
  - Database name generation
  - Migration and seeding utilities

---

## 🛠️ Technical Implementation

### Architecture Components

#### 1. **Tenant Model** (`app/Models/Tenant.php`)
- Extends Spatie's base Tenant model
- Methods for database type checking
- Auto-creates databases on tenant creation
- Provides database configuration

#### 2. **Database Switching Task** (`app/Multitenancy/Tasks/SwitchTenantDatabaseTask.php`)
- Implements custom database switching logic
- Handles both single and multi-database modes
- Manages connection configuration dynamically

#### 3. **Tenant Finder** (`app/Multitenancy/TenantFinder/DomainTenantFinder.php`)
- Identifies tenants by domain
- Used by middleware for automatic detection

#### 4. **Middleware** (`app/Http/Middleware/InitializeTenancyByDomain.php`)
- Intercepts all requests
- Identifies and switches tenant context
- Registered globally in bootstrap/app.php

#### 5. **Service Class** (`app/Services/TenantDatabaseService.php`)
- Centralized tenant management
- Handles migrations, seeding, database operations
- Provides utility methods

---

## 📁 File Structure

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
    │   └── InitializeTenancyByDomain.php  # Tenant identification
    └── Controllers/
        └── TenantDashboardController.php   # Example controller

config/
└── multitenancy.php                        # Package configuration

database/
├── migrations/
│   ├── landlord/
│   │   └── 2025_11_23_112523_create_landlord_tenants_table.php
│   └── tenant/
│       └── 2025_11_23_000001_create_tenant_posts_table.php
└── seeders/
    └── TenantSeeder.php

routes/
└── web.php                                 # Landlord & tenant routes
```

---

## 🗄️ Database Schema

### Tenants Table
```sql
- id (bigint, primary key)
- name (varchar)
- domain (varchar, unique)
- database_type (enum: 'single', 'multi')
- database_name (varchar, nullable)
- database_username (varchar, nullable)
- database_password (varchar, nullable)
- database (varchar, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

---

## 🚀 Quick Start

### 1. Installation
```bash
composer require spatie/laravel-multitenancy
php artisan migrate
```

### 2. Create Your First Tenant
```bash
php artisan tinker
```

**Single-Database Tenant:**
```php
Tenant::create([
    'name' => 'Acme Corp',
    'domain' => 'acme.myapp.test',
    'database_type' => 'single',
]);
```

**Multi-Database Tenant:**
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

### 3. Run Migrations
```php
use App\Services\TenantDatabaseService;

$service = app(TenantDatabaseService::class);
$tenant = Tenant::find(1);
$service->runMigrations($tenant);
```

### 4. Test It
- Update hosts file: `127.0.0.1 acme.myapp.test`
- Start server: `php artisan serve`
- Visit: `http://acme.myapp.test:8000/dashboard`

---

## 🌐 API Routes

### Landlord Routes (Main App)
- `GET /` - Main application info
- `GET /api/tenants` - List all tenants

### Tenant Routes (Tenant-Specific)
- `GET /dashboard` - Tenant dashboard
- `GET /api/tenant/info` - Current tenant information

---

## 📚 Documentation Files

1. **MULTITENANCY_SETUP.md** - Complete setup guide
2. **QUICK_START.md** - Quick reference
3. **TINKER_EXAMPLES.php** - 20+ tinker examples

---

## 🎯 Use Cases

### Single-Database Mode
- Small to medium applications
- Simpler architecture
- Lower infrastructure cost
- Shared resources across tenants
- Uses global scopes for data isolation

### Multi-Database Mode
- Large applications with many tenants
- Complete data isolation
- Better performance for large datasets
- Easier tenant data export/backup
- Separate database per tenant

---

## 🔐 Security Considerations

1. **Database Credentials**: Consider encrypting in production
2. **Database User Creation**: Disabled by default (enable if needed)
3. **Database Deletion**: Manual confirmation required
4. **Tenant Isolation**: Enforced at database/application level
5. **Domain Validation**: Implemented in tenant finder

---

## 🧪 Testing

The system includes:
- Example routes for both landlord and tenant contexts
- Test endpoints for tenant information
- Sample migrations and seeders
- Comprehensive tinker examples

---

## 📊 Performance

- **Single-DB Mode**: All queries on one connection
- **Multi-DB Mode**: Connection pooling per tenant
- **Caching**: Tenant-prefixed cache keys
- **Connection**: Lazy connection switching

---

## 🔄 Migration Path

### From Single to Multi-Database
```php
// 1. Update tenant record
$tenant->update([
    'database_type' => 'multi',
    'database_name' => 'new_db_name',
    'database_username' => 'root',
    'database_password' => 'password',
]);

// 2. Create database and run migrations
$service->runMigrations($tenant);

// 3. Migrate data (manual process)
```

---

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| Tenant not found | Check domain in database, hosts file |
| Database error | Verify credentials, ensure DB exists |
| Wrong data | Check `Tenant::current()`, verify connection |
| Middleware not working | Check bootstrap/app.php registration |

---

## 📖 Additional Resources

- [Spatie Multitenancy Docs](https://spatie.be/docs/laravel-multitenancy/v4/introduction)
- [Laravel 12 Docs](https://laravel.com/docs/12.x)
- See MULTITENANCY_SETUP.md for detailed guide

---

## ✅ What's Included

- [x] Spatie Laravel Multitenancy package
- [x] Custom tenant model with dual mode support
- [x] Database switching task implementation
- [x] Domain-based tenant finder
- [x] Automatic tenant identification middleware
- [x] Tenant management service
- [x] Example routes and controllers
- [x] Migration structure (landlord + tenant)
- [x] Seeding examples
- [x] Comprehensive documentation
- [x] 20+ tinker usage examples

---

## 🎓 Best Practices

1. Always check tenant context before operations
2. Use `Tenant::current()` to get active tenant
3. Separate landlord and tenant migrations
4. Test both single and multi-database modes
5. Use service class for tenant operations
6. Implement proper error handling
7. Consider caching for performance

---

## 📝 Configuration

Key settings in `config/multitenancy.php`:
- `tenant_finder`: DomainTenantFinder
- `tenant_model`: App\Models\Tenant
- `tenant_database_connection_name`: 'tenant'
- `landlord_database_connection_name`: 'mysql'
- `switch_tenant_tasks`: Custom switching logic

---

## 🚀 Production Checklist

- [ ] Update database credentials management
- [ ] Configure proper domain routing
- [ ] Set up tenant-specific queues
- [ ] Implement tenant-aware jobs
- [ ] Configure cache prefixing
- [ ] Set up monitoring per tenant
- [ ] Plan backup strategy
- [ ] Test failover scenarios
- [ ] Document tenant onboarding process
- [ ] Set up tenant offboarding process

---

**Built with Laravel 12, PHP 8.2+, and Spatie Laravel Multitenancy v4.0.7**

*Ready for production use! 🎉*

