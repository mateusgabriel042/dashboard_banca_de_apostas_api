<?php

namespace App\Services;

use Illuminate\Http\Request;

class SportService extends AbstractService {
    private $role;
    private $model;

    public function __construct($role, $model){
        parent::__construct($role, $model);
        $this->role = $role;
        $this->model = $model;
    }

    public function getAll($relations = []){
        $this->checkAccess($this->role.'-view');
        return $this->model->with($relations)->where('is_active', '=', 1)->paginate(20);
    }
}