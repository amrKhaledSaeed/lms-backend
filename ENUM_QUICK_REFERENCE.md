# DatabaseType Enum - Quick Reference Card

## 🎯 Quick Import

```php
use App\Enums\DatabaseType;
```

---

## 📊 Enum Values

| Case | Value | Label | String |
|------|-------|-------|--------|
| `DatabaseType::SINGLE` | `1` | "Single Database" | "single" |
| `DatabaseType::MULTI` | `2` | "Multi Database" | "multi" |

---

## ⚡ Quick Usage

### Create Tenant
```php
Tenant::create([
    'database_type' => DatabaseType::SINGLE, // or DatabaseType::MULTI
    // ... other fields
]);
```

### Query Tenants
```php
Tenant::where('database_type', DatabaseType::MULTI)->get();
```

### Check Type
```php
if ($tenant->database_type === DatabaseType::MULTI) { }
// or
if ($tenant->isMultiDatabase()) { }
```

### Get Values
```php
$tenant->database_type->value;        // 1 or 2
$tenant->database_type->label();      // "Single Database"
$tenant->database_type->stringValue(); // "single"
```

---

## 🔧 Enum Methods

| Method | Returns | Example |
|--------|---------|---------|
| `value` | `int` | `1` or `2` |
| `label()` | `string` | `"Single Database"` |
| `stringValue()` | `string` | `"single"` |
| `description()` | `string` | Full description |

---

## 📦 Static Methods

```php
// Get all options (for dropdowns)
DatabaseType::options()
// Returns: [1 => "Single Database", 2 => "Multi Database"]

// Get complete array
DatabaseType::toArray()
// Returns: Array with all details

// Convert from string
DatabaseType::fromString('single')  // Returns DatabaseType::SINGLE
DatabaseType::fromString('multi')   // Returns DatabaseType::MULTI
```

---

## 🔍 API Response Format

```json
{
  "database_type": {
    "value": 1,
    "label": "Single Database",
    "string_value": "single",
    "description": "All tenants share the main database"
  }
}
```

---

## 💡 Common Patterns

**Form Validation:**
```php
'database_type' => 'required|integer|in:1,2'
```

**Select Options:**
```php
$options = DatabaseType::options();
```

**Batch Query:**
```php
$multiTenants = Tenant::where('database_type', DatabaseType::MULTI)->get();
```

**Convert String (API Input):**
```php
$type = DatabaseType::fromString($request->input('type'));
```

---

## 🎨 In Controller

```php
return response()->json([
    'database_type' => [
        'value' => $tenant->database_type->value,
        'label' => $tenant->database_type->label(),
    ],
]);
```

---

## 📝 Remember

- ✅ Always use `DatabaseType::SINGLE` or `DatabaseType::MULTI`
- ✅ Never use raw integers (`1`, `2`) in comparisons
- ✅ Use `->label()` for display
- ✅ Use `->value` for integer value
- ✅ Use `fromString()` for API input conversion

---

**Print this for quick reference! 📋**

