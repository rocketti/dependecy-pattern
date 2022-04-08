<?php

namespace App\Rocketti\Repositories;

use App\Rocketti\Models\Teste;

class TesteRepository
{
    /**
     * @var Role
     */
    private $model;

    /**
     * Teste Repository constructor.
     * @param Teste $example
     */
    public function __construct(Teste $teste)
    {
        $this->model = $Teste;
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->model->get();
    }
    
    /**
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->model->find($id);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        return $this->model->create($data);
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        return $this->model->find($id)->fill($data)->save();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->model->find($id)->delete();
    }
}