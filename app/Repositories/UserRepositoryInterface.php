<?php

namespace App\Repositories;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function all();
    public function lists($data);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
