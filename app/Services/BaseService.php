<?php

namespace App\Services;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseService
{
    protected $repository;

    public function __construct(BaseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->all();
    }

    public function getById($id)
    {
        return $this->repository->find($id);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }

    /**
     * Get model detail.
     *
     * @param Model $entity
     *
     * @return Model
     */
    public function detail(Model $entity, $relations = [])
    {
        return $this->repository->detail($entity, $relations);
    }
}
