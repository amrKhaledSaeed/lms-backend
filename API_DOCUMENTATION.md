# 🚀 LMS Backend API Documentation

## Base URL

```
http://localhost:8000/api
```

---

## 📋 Table of Contents

1. [Landlord API Routes](#landlord-api-routes)
2. [Tenant API Routes](#tenant-api-routes)
3. [Authentication](#authentication)
4. [Response Format](#response-format)
5. [Error Handling](#error-handling)

---

## 🏢 Landlord API Routes

These routes don't require tenant context and operate on the landlord database.

### Get API Info

**Endpoint:** `GET /api/landlord/`

**Response:**
```json
{
  "message": "LMS Backend API",
  "version": "1.0.0",
  "app": "Laravel",
  "tenants_count": 5
}
```

---

### List All Tenants

**Endpoint:** `GET /api/landlord/tenants`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Acme Corp",
      "domain": "acme.myapp.test",
      "database_type": {
        "value": 1,
        "label": "Single Database",
        "string_value": "single"
      },
      "is_multi_database": false,
      "created_at": "2025-11-23T12:30:00.000000Z"
    },
    {
      "id": 2,
      "name": "TechStart Inc",
      "domain": "techstart.myapp.test",
      "database_type": {
        "value": 2,
        "label": "Multi Database",
        "string_value": "multi"
      },
      "is_multi_database": true,
      "created_at": "2025-11-23T12:35:00.000000Z"
    }
  ],
  "count": 2
}
```

---

### Get Single Tenant

**Endpoint:** `GET /api/landlord/tenants/{id}`

**Parameters:**
- `id` (integer, required) - Tenant ID

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Acme Corp",
    "domain": "acme.myapp.test",
    "database_type": {
      "value": 1,
      "label": "Single Database",
      "string_value": "single",
      "description": "All tenants share the main database"
    },
    "database_name": null,
    "is_multi_database": false,
    "created_at": "2025-11-23T12:30:00.000000Z",
    "updated_at": "2025-11-23T12:30:00.000000Z"
  }
}
```

**Error Response (404):**
```json
{
  "success": false,
  "message": "Tenant not found"
}
```

---

### Get Database Types

**Endpoint:** `GET /api/landlord/database-types`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "value": 1,
      "label": "Single Database",
      "string_value": "single",
      "description": "All tenants share the main database"
    },
    {
      "value": 2,
      "label": "Multi Database",
      "string_value": "multi",
      "description": "Each tenant has a separate database"
    }
  ]
}
```

---

## 🏘️ Tenant API Routes

These routes require tenant context (accessed via tenant domain).

**Base URL:** `http://{tenant-domain}/api/tenant`

Example: `http://acme.myapp.test:8000/api/tenant`

---

### Get Tenant Dashboard

**Endpoint:** `GET /api/tenant/dashboard`

**Headers:**
```
Host: acme.myapp.test
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Welcome to your tenant dashboard!",
  "data": {
    "tenant": {
      "id": 1,
      "name": "Acme Corp",
      "domain": "acme.myapp.test",
      "database_type": {
        "value": 1,
        "label": "Single Database",
        "string_value": "single",
        "description": "All tenants share the main database"
      },
      "database_name": null
    },
    "database_connection": "mysql",
    "is_multi_database": false
  }
}
```

**Error Response (404):**
```json
{
  "success": false,
  "message": "No tenant found",
  "error": "Please access this via a tenant domain"
}
```

---

### Get Current Tenant Info

**Endpoint:** `GET /api/tenant/info`

**Headers:**
```
Host: acme.myapp.test
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Acme Corp",
    "domain": "acme.myapp.test",
    "database_type": {
      "value": 1,
      "label": "Single Database"
    },
    "connection": "mysql",
    "is_multi_database": false
  }
}
```

**Error Response (404):**
```json
{
  "success": false,
  "message": "No tenant context found",
  "error": "Please access this API via a tenant domain"
}
```

---

### Get Tenant Stats

**Endpoint:** `GET /api/tenant/stats`

**Headers:**
```
Host: acme.myapp.test
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "tenant_id": 1,
    "tenant_name": "Acme Corp",
    "database_type": "Single Database"
  }
}
```

---

## 🔐 Authentication

Currently, the API doesn't require authentication. To add authentication:

1. **Install Laravel Sanctum:**
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

2. **Add middleware to routes:**
```php
Route::middleware('auth:sanctum')->group(function () {
    // Protected routes
});
```

---

## 📊 Response Format

All API responses follow a consistent format:

### Success Response
```json
{
  "success": true,
  "message": "Optional message",
  "data": {
    // Response data
  },
  "count": 10  // Optional, for lists
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "error": "Detailed error description"
}
```

---

## ⚠️ Error Handling

### HTTP Status Codes

| Code | Description |
|------|-------------|
| 200 | Success |
| 404 | Resource not found |
| 422 | Validation error |
| 500 | Server error |

### Common Errors

#### Tenant Not Found
```json
{
  "success": false,
  "message": "No tenant context found",
  "error": "Please access this API via a tenant domain"
}
```

---

## 🧪 Testing with cURL

### Landlord Routes

```bash
# Get all tenants
curl http://localhost:8000/api/landlord/tenants

# Get single tenant
curl http://localhost:8000/api/landlord/tenants/1

# Get database types
curl http://localhost:8000/api/landlord/database-types
```

### Tenant Routes

```bash
# Get tenant dashboard
curl -H "Host: acme.myapp.test" http://localhost:8000/api/tenant/dashboard

# Get tenant info
curl -H "Host: acme.myapp.test" http://localhost:8000/api/tenant/info

# Get tenant stats
curl -H "Host: acme.myapp.test" http://localhost:8000/api/tenant/stats
```

---

## 📱 Testing with Postman

### Landlord Routes
1. Set URL: `http://localhost:8000/api/landlord/tenants`
2. Method: GET
3. Send

### Tenant Routes
1. Set URL: `http://localhost:8000/api/tenant/dashboard`
2. Add Header:
   - Key: `Host`
   - Value: `acme.myapp.test`
3. Method: GET
4. Send

---

## 🔧 CORS Configuration

If accessing from a frontend app, configure CORS in `config/cors.php`:

```php
'paths' => ['api/*'],
'allowed_methods' => ['*'],
'allowed_origins' => ['http://localhost:3000'],
'allowed_headers' => ['*'],
```

---

## 📝 Rate Limiting

Laravel includes rate limiting by default. Configure in `app/Providers/RouteServiceProvider.php`:

```php
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
});
```

---

## 🌐 Production URLs

When deploying to production, update:

**Base URL:** `https://api.yourdomain.com/api`

**Tenant URLs:** `https://{tenant}.yourdomain.com/api/tenant`

---

## 📦 Health Check

**Endpoint:** `GET /api/health`

**Response:**
```json
{
  "status": "ok",
  "timestamp": "2025-11-23T12:30:00+00:00"
}
```

---

## 🚀 Next Steps

1. ✅ Add authentication (Laravel Sanctum)
2. ✅ Add validation
3. ✅ Add pagination for list endpoints
4. ✅ Add filtering and sorting
5. ✅ Add request/response logging
6. ✅ Add API versioning (v1, v2)
7. ✅ Add API documentation (Swagger/OpenAPI)

---

**API Version:** 1.0.0  
**Last Updated:** November 23, 2025

