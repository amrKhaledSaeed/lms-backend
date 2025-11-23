# DatabaseType Enum Usage Guide

## Overview

The `database_type` field uses a **PHP Backed Enum** (`DatabaseType`) instead of a database enum. This provides better type safety, IDE autocomplete, and easier refactoring.

---

## Enum Definition

**File:** `app/Enums/DatabaseType.php`

```php
enum DatabaseType: int
{
    case SINGLE = 1;
    case MULTI = 2;
}
```

### Values
- `1` = Single Database Mode
- `2` = Multi Database Mode

---

## Usage in Code

### Creating Tenants

```php
use App\Models\Tenant;
use App\Enums\DatabaseType;

// Method 1: Using enum directly
$tenant = Tenant::create([
    'name' => 'Acme Corp',
    'domain' => 'acme.myapp.test',
    'database_type' => DatabaseType::SINGLE,
]);

// Method 2: Using integer value
$tenant = Tenant::create([
    'name' => 'Tech Corp',
    'domain' => 'tech.myapp.test',
    'database_type' => 1, // Will be cast to DatabaseType::SINGLE
]);

// Method 3: Using string (with helper method)
$tenant = Tenant::create([
    'name' => 'Multi Corp',
    'domain' => 'multi.myapp.test',
    'database_type' => DatabaseType::fromString('multi'), // Returns DatabaseType::MULTI
]);
```

### Checking Database Type

```php
$tenant = Tenant::find(1);

// Method 1: Using helper methods
if ($tenant->isMultiDatabase()) {
    echo "This tenant uses separate database";
}

if ($tenant->isSingleDatabase()) {
    echo "This tenant uses shared database";
}

// Method 2: Direct comparison
if ($tenant->database_type === DatabaseType::MULTI) {
    echo "Multi-database tenant";
}

// Method 3: Using value
if ($tenant->database_type->value === 2) {
    echo "Multi-database tenant";
}
```

### Getting Labels and Values

```php
$tenant = Tenant::find(1);

// Get the integer value (1 or 2)
$value = $tenant->database_type->value;

// Get the display label
$label = $tenant->database_type->label();
// Returns: "Single Database" or "Multi Database"

// Get the string value (for backward compatibility)
$stringValue = $tenant->database_type->stringValue();
// Returns: "single" or "multi"

// Get the description
$description = $tenant->database_type->description();
// Returns: "All tenants share the main database" or "Each tenant has a separate database"

// Using model helper methods
$label = $tenant->getDatabaseTypeLabel();
$description = $tenant->getDatabaseTypeDescription();
```

### Returning in API Responses

```php
return response()->json([
    'tenant' => [
        'id' => $tenant->id,
        'name' => $tenant->name,
        'database_type' => [
            'value' => $tenant->database_type->value,
            'label' => $tenant->database_type->label(),
            'string_value' => $tenant->database_type->stringValue(),
            'description' => $tenant->database_type->description(),
        ],
    ],
]);

// Output:
// {
//   "tenant": {
//     "id": 1,
//     "name": "Acme Corp",
//     "database_type": {
//       "value": 1,
//       "label": "Single Database",
//       "string_value": "single",
//       "description": "All tenants share the main database"
//     }
//   }
// }
```

---

## Static Enum Methods

### Get All Options

```php
use App\Enums\DatabaseType;

// Get options for select dropdown
$options = DatabaseType::options();
// Returns: [1 => "Single Database", 2 => "Multi Database"]

// Get all cases with details
$all = DatabaseType::toArray();
// Returns:
// [
//   [
//     'value' => 1,
//     'label' => 'Single Database',
//     'string_value' => 'single',
//     'description' => 'All tenants share the main database'
//   ],
//   [
//     'value' => 2,
//     'label' => 'Multi Database',
//     'string_value' => 'multi',
//     'description' => 'Each tenant has a separate database'
//   ]
// ]
```

### Convert from String

```php
use App\Enums\DatabaseType;

$type = DatabaseType::fromString('single');
// Returns: DatabaseType::SINGLE

$type = DatabaseType::fromString('multi');
// Returns: DatabaseType::MULTI

$type = DatabaseType::fromString('invalid');
// Returns: null
```

---

## Querying with Enum

```php
use App\Models\Tenant;
use App\Enums\DatabaseType;

// Find all single-database tenants
$singleTenants = Tenant::where('database_type', DatabaseType::SINGLE)->get();

// Find all multi-database tenants
$multiTenants = Tenant::where('database_type', DatabaseType::MULTI)->get();

// Or using integer values
$singleTenants = Tenant::where('database_type', 1)->get();
$multiTenants = Tenant::where('database_type', 2)->get();

// Count by type
$singleCount = Tenant::where('database_type', DatabaseType::SINGLE)->count();
$multiCount = Tenant::where('database_type', DatabaseType::MULTI)->count();
```

---

## Updating Tenants

```php
$tenant = Tenant::find(1);

// Update using enum
$tenant->update([
    'database_type' => DatabaseType::MULTI,
]);

// Update using integer
$tenant->update([
    'database_type' => 2,
]);

// Update using string helper
$tenant->update([
    'database_type' => DatabaseType::fromString('multi'),
]);
```

---

## In Blade Views (if using)

```blade
<div>
    <h2>{{ $tenant->name }}</h2>
    <p>Type: {{ $tenant->database_type->label() }}</p>
    <p>Description: {{ $tenant->database_type->description() }}</p>
    
    @if($tenant->isMultiDatabase())
        <span class="badge badge-primary">Multi Database</span>
    @else
        <span class="badge badge-secondary">Single Database</span>
    @endif
</div>
```

---

## In Form Validation

```php
use App\Enums\DatabaseType;
use Illuminate\Validation\Rule;

$validated = $request->validate([
    'name' => 'required|string',
    'domain' => 'required|string|unique:tenants',
    'database_type' => ['required', Rule::in([
        DatabaseType::SINGLE->value,
        DatabaseType::MULTI->value,
    ])],
]);

// Or more flexible validation
$validated = $request->validate([
    'database_type' => 'required|integer|in:1,2',
]);
```

---

## Tinker Examples

```bash
php artisan tinker
```

```php
use App\Models\Tenant;
use App\Enums\DatabaseType;

// Create with enum
$tenant = Tenant::create([
    'name' => 'Test Tenant',
    'domain' => 'test.myapp.test',
    'database_type' => DatabaseType::SINGLE,
]);

// Get enum details
$tenant->database_type->value;        // 1
$tenant->database_type->label();      // "Single Database"
$tenant->database_type->stringValue(); // "single"

// Check type
$tenant->isMultiDatabase(); // false
$tenant->isSingleDatabase(); // true

// Get all enum options
DatabaseType::options();
DatabaseType::toArray();

// Convert from string
DatabaseType::fromString('single'); // DatabaseType::SINGLE
DatabaseType::fromString('multi');  // DatabaseType::MULTI

// Query
Tenant::where('database_type', DatabaseType::MULTI)->count();
```

---

## Migration

The database stores `database_type` as `tinyInteger`:

```php
$table->tinyInteger('database_type')->default(1);
```

- Value `1` = Single Database
- Value `2` = Multi Database

---

## Benefits of Using Enum

1. ✅ **Type Safety**: IDE knows the possible values
2. ✅ **Autocomplete**: Better developer experience
3. ✅ **Refactoring**: Easy to rename or add cases
4. ✅ **Validation**: Built-in type checking
5. ✅ **Documentation**: Self-documenting code
6. ✅ **Consistency**: Single source of truth
7. ✅ **Labels**: Easy display in UI without hardcoding

---

## Common Patterns

### Select Dropdown Data

```php
// Controller
public function create()
{
    return view('tenants.create', [
        'databaseTypes' => DatabaseType::options(),
    ]);
}
```

### API Endpoint for Enum Values

```php
Route::get('/api/database-types', function () {
    return response()->json([
        'types' => DatabaseType::toArray(),
    ]);
});
```

### Form Request Validation

```php
class StoreTenantRequest extends FormRequest
{
    public function rules()
    {
        return [
            'database_type' => ['required', 'integer', Rule::in([
                DatabaseType::SINGLE->value,
                DatabaseType::MULTI->value,
            ])],
        ];
    }
}
```

---

## Migration from String to Enum

If you're migrating from string-based database type:

```php
use App\Models\Tenant;
use App\Enums\DatabaseType;

// Update existing records
Tenant::where('database_type', 'single')->update(['database_type' => DatabaseType::SINGLE->value]);
Tenant::where('database_type', 'multi')->update(['database_type' => DatabaseType::MULTI->value]);
```

---

**Best Practice:** Always use the enum constants (`DatabaseType::SINGLE`, `DatabaseType::MULTI`) in your code instead of raw integers for better readability and maintainability.

