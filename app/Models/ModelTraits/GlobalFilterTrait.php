<?php
namespace App\Models\ModelTraits;

use App\Modules\Landlord\Enums\Tenant\TenantStatusEnum;
use App\Modules\Landlord\Models\CustomTenant;
use Illuminate\Database\Eloquent\Builder;

trait GlobalFilterTrait{
    public function scopeFilter(Builder $query, $request)
    {
        // Simple filters for exact matches
            //for filter
        foreach ($this->filters as $filter) {
            $query->when($request->filled($filter), function ($q) use ($filter, $request) {
                    $q->where($filter, $request->$filter);
            });
        }

        // Quota-specific filters
        foreach ($this->filterWithOperatorAndValue as $filter) {
            $query->when($request->filled($filter), function ($q) use ($filter, $request) {
                $q->where($filter, $request->$filter['operator'], $request->$filter['value']);
            });
        }

            $this->customQuery($query, $request);
        return $query;
    }
}
