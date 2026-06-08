<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function lists($data)
    {
        // Pass input data from $request
        $input = collect($data);
        $config = config('constant');
        // select list column

        // Pass the fields to be displayed as array
        $entities = $this->model->select(isset($data['select']) ? $data['select'] : ['*']);

        // load realtion counts
        if (isset($data['relationcounts']) && count($data['relationcounts'])) {
            $entities = $entities->withCount($data['relationcounts']);
        }

        // load relations
        if (isset($data['relations']) && count($data['relations'])) {
            $entities = $entities->with($data['relations']);
        }
        // filter list by condition
        $condition = $input->has('condition') ? $input['condition'] : $input;
        if (count($condition) && method_exists($this, 'search')) {
            foreach ($condition as $key => $value) {

                $entities = $this->search($entities, $key, $value);
            }
        }

        // order list
        $orderBy = $input->has('sort') && in_array($input['sort'], $this->model->sortable) ? $input['sort'] : $this->model->getKeyName();
        $entities = $entities->orderBy($orderBy, $input->has('sortType') && $input['sortType'] == 1 ? 'asc' : 'desc');

        // limit result
        $limit = $input->has('limit') ? (int) $input['limit'] : $config['paginate'];
        if ($limit) {
            return $entities->paginate($limit);
        }

        return $entities->get();
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $model = $this->find($id);
        $model->update($data);
        return $model;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }
}
