<?php

namespace App\Http\Middleware;

use App\Multitenancy\TenantFinder\DomainTenantFinder;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InitializeTenancyByDomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Find the tenant for this request
        $tenantFinder = app(DomainTenantFinder::class);
        $tenant = $tenantFinder->findForRequest($request);

        if ($tenant) {
            // Make this tenant current
            $tenant->makeCurrent();
        }

        return $next($request);
    }
}
