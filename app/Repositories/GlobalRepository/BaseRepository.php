<?php

namespace App\Repositories\GlobalRepository;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseRepository
{
    protected Model $model;

    /**
     * Fetch all records with optional filters, relations and pagination.
     *
     * @param Request|null $request
     * @param array $relations
     * @param int|null $perPage
     * @param string $orderColumn
     * @param string $orderDirection
     * @param Builder|null $query
     * @return LengthAwarePaginator|Collection
     */
    public function all(
        ?Request $request = null,
        array $relations = [],
        ?int $perPage = null,
        ?string $orderColumn = 'id',
        ?string $orderDirection = 'DESC',
        ?Builder $query = null,
        ?array $select = [],
    ): LengthAwarePaginator|Collection {
        $builder = $this->applyOrdering($query ?? $this->model->newQuery(), $orderColumn ?? 'id', $orderDirection ?? 'desc');
        $builder = $this->applyFilters($builder, $request);
        $builder = $this->applyRelations($builder, $relations);
        $builder = $this->applySelection($builder, $select);
        return $perPage ? $builder->paginate($perPage) : $builder->get();
    }
    
    /**
     * Apply ordering to the query.
     */
    protected function applyOrdering(Builder $builder, ?string $column, ?string $direction): Builder
    {
        return $builder->orderBy($column, $direction);
    }
    
    /**
     * Apply filters from request.
     */
    protected function applyFilters(Builder $builder, ?Request $request): Builder
    {
        if ($request?->has('filter')) {
            $builder = $builder->filter($request);
        }

        return $builder;
    }
    
    /**
     * Apply eager loaded relations.
     */
    protected function applyRelations(Builder $builder, array $relations): Builder
    {
        return !empty($relations) ? $builder->with($relations) : $builder;
    }
    protected function applySelection(Builder $builder, array $select): Builder
    {
        return !empty($select) ? $builder->select($select) : $builder;
    }

    //----------------------------------------------end all function and it helers function-------------------------

    public function QueryByVarityTypeOfConditions(
        string $whereIncolumn = null,
        array $WhereInvalues = null,
        $criteria = [],
        $relations = [],
        $selectColumns = ['*'],
        $request = null
    ) {
        $query = $this->model->query();
        if ($request && $request->has('filter')) {
            $query = $query->filter($request);
        }

        if ($whereIncolumn != null && $WhereInvalues != null) {
            $query->whereIn($whereIncolumn, $WhereInvalues);
        }
        return $query->select($selectColumns)->where($criteria)->with($relations);
    }

    public function dataGet($query)
    {
        return $query->get();
    }

    public function dataFirst($query)
    {
        return $query->first();
    }

    public function dataCount($query)
    {
        return $query->count();
    }

    public function dataSum($query, $columnName)
    {
        return $query->sum($columnName);
    }
    //-------------------------------------------------------end query by varity------------------------------------------
    public function select(array $columns = ['*'], array $relations = [], ?Request $request = null)
    {
        $collection = $this->model->select($columns);

        if (!empty($relations)) {
            $collection = $collection->with($relations);
        }

        if ($request && $request->has('filter')) {
            $collection = $collection->filter($request);
        }

        return $collection->get();
    }

    // public function findWithRelations($id, array $relations = []): ?Model
    // {
    //     return $this->model->with($relations)->find($id);
    // }

    public function find($id, $relations = []): ?Model
    {
        return $this->model->with($relations)->find($id);
    }

    public function findBy(array $criteria, ?Request $request = null, ?array $relations = [], ?int $perPage = null, ?string $orderColumn = 'id', ?string $orderDirection  = 'DESC')
    {
        $query = $this->model->newQuery()->where($criteria);
        return $this->all($request, $relations, $perPage, $orderColumn, $orderDirection, $query);
    }

    public function getCount(array $criteria = [])
    {
        $query = $this->model;

        $query = $query->where($criteria);

        return $query->count();
    }

    public function create(array $data): ?Model
    {
        foreach ($data as $field => $val) {
            $this->model->{$field} = $val;
        }

        if ($this->model->save()) {
            return $this->model;
        }

        return null;
    }

    public function insert(array $data): bool
    {
        return $this->model->insert($data);
    }

    public function update(array $data): ?Model
    {
        $model = $this->find($data['id'] ?? 0);

        if ($model) {
            foreach ($data as $field => $val) {
                if ($field === 'id') {
                    continue;
                }
                $model->{$field} = $val;
            }

            if ($model->save()) {
                return $model;
            }
        }

        return null;
    }

    public function updateList(array $data, string $columnName, array $columnValue): bool
    {
        return $this->model->whereIn($columnName, $columnValue)->update($data);
    }

    public function delete($id): bool
    {
        $record = $this->find($id);
         if (!$record) {
            return false; // record not found
        }

        return $record->delete();
    }

    public function deleteBy(array $criteria): bool
    {
        return $this->model->where($criteria)->delete();
    }

    public function getNextId(): int
    {
        return $this->model->max('id') + 1;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function setModel(Model $model): void
    {
        $this->model = $model;
    }
}
