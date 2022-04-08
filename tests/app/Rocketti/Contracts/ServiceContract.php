<?php

namespace App\Rocketti\Contracts;
            
interface ServiceContract
{
    /**
    * @return mixed
     */
    public function renderList();

    /**
     * @param $id
     * @return mixed
     */
    public function renderEdit($id);

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function buildUpdate($id, $data);

    /**
     * @param $data
     * @return mixed
     */
    public function buildInsert($data);

    /**
     * @param $id
     * @return mixed
     */
    public function buildDelete($id);
}