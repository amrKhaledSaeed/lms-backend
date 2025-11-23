<?php

namespace App\Multitenancy\TenantFinder;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;
use Spatie\Multitenancy\TenantFinder\TenantFinder;

class DomainTenantFinder extends TenantFinder
{
    use UsesTenantModel;

    /**
     * Find the tenant for the given request.
     */
    public function findForRequest(Request $request): ?Tenant
    {
        $host = $request->getHost();

        // Try to find tenant by exact domain match
        return $this->getTenantModel()::where('domain', $host)->first();
    }
}

