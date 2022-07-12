<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Traits\CheckAccess;

class AbstractService {
    use CheckAccess;
    private $role;
    private $model;

    public function __construct($role, $model){
        $this->role = $role;
        $this->model = $model;
    }

    public function getData(){
        $data = [
            'count' => $this->model->all()->count(),
        ];
        return $data;
    }

    public function getAll($relations = []){
        $this->checkAccess($this->role.'-view');
        return $this->model->with($relations)->paginate(20);
    }

    public function getAllNotPaginate($relations = [], $colOrderBy = null){
        $this->checkAccess($this->role.'-view');
        if($colOrderBy == null)
            return $this->model->orderBy('id','ASC')->get();
        else
            return $this->model->orderBy($colOrderBy,'ASC')->get();
    }

    public function create($request){
    	$this->checkAccess($this->role.'-create');
    	$dataRequest = $request->all();
        $objectRegistered = $this->model->create($dataRequest);
        return $objectRegistered;
    }

    public function find($id, $relations = []){
        $this->checkAccess($this->role.'-view');
        return $this->model->with($relations)->findOrFail($id);
    }

    public function search($option, $value, $relations = []) {
        $this->checkAccess($this->role.'-view');
        $objectsSelected = $this->model->with($relations)->where($option,'LIKE',"%{$value}%")->paginate(20);
        return $objectsSelected;
    }

    public function update($request, $id){
        $this->checkAccess($this->role.'-update');
        $objectEdit = $this->model->find($id);
        $dataRequest = $request->all();
        $objectEdit->update($dataRequest);
        return $objectEdit;
    }

    public function delete($id){
        $this->checkAccess($this->role.'-delete');
    	$objectRegistered = $this->model->find($id);
        $objectRegistered->delete();
        return $objectRegistered;
    }

}