# 🚀 API Quick Reference

## Base URL
```
http://localhost:8000/api
```

---

## 🏢 Landlord Endpoints (No Tenant Required)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/landlord/` | API info |
| GET | `/landlord/tenants` | List all tenants |
| GET | `/landlord/tenants/{id}` | Get single tenant |
| GET | `/landlord/database-types` | Get database type options |

---

## 🏘️ Tenant Endpoints (Requires Tenant Domain)

**Base:** `http://{tenant-domain}:8000/api/tenant`

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/tenant/dashboard` | Tenant dashboard |
| GET | `/tenant/info` | Current tenant info |
| GET | `/tenant/stats` | Tenant statistics |

---

## 🧪 Quick Test Commands

### Using cURL

```bash
# Landlord: List tenants
curl http://localhost:8000/api/landlord/tenants

# Tenant: Get dashboard
curl -H "Host: acme.myapp.test" http://localhost:8000/api/tenant/dashboard
```

### Using Postman
1. **Landlord:** Just use `http://localhost:8000/api/landlord/tenants`
2. **Tenant:** Add Header `Host: acme.myapp.test` with URL `http://localhost:8000/api/tenant/dashboard`

---

## 📊 Response Format

**Success:**
```json
{
  "success": true,
  "data": { ... }
}
```

**Error:**
```json
{
  "success": false,
  "message": "Error message"
}
```

---

## 🔑 Status Codes

| Code | Meaning |
|------|---------|
| 200 | Success |
| 404 | Not Found |
| 422 | Validation Error |
| 500 | Server Error |

---

**Print this for quick reference! 📋**

