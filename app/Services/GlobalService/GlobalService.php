<?php
namespace App\Services\GlobalService;

use App\Repositories\AddressBookRepository;
use App\Repositories\GlobalRepository\GlobalRepository;


class GlobalService
{
    private GlobalRepository $globalRepository;
    /**
     * Create a new service instance.
     *
     * @param  $model
     */
    public function __construct($model)
    {
        $this->globalRepository = new GlobalRepository($model);
    }

    public function listData($request = null, array $relation = [], $perPage = null, $orderColumn = null, $orderDirection  = null, $query = null, $select = [])
    {
        return $this->globalRepository->all($request,  $relation, $perPage, $orderColumn, $orderDirection, $query, $select);
    }

    public function show($id, $relations = [])
    {
        return $this->globalRepository->find($id, $relations);
    }

    public function create(array $request)
    {
        return $this->globalRepository->create($request);
    }


    public function update(array $data, $id)
    {
        $data['id'] = $id;
        return $this->globalRepository->update($data);
    }


    public function delete(int $id)
    {
        return $this->globalRepository->delete($id);
    }
}
