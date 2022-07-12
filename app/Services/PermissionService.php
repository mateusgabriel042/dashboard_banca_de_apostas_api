<?php

namespace App\Services;

use App\Models\Permission;
use App\Http\Requests\PermissionRegisterRequest;
use App\Http\Requests\PermissionUpdateRequest;
use App\Traits\CheckAccess;

class PermissionService {
    use CheckAccess;

    public function getAll(){
        $this->checkAccess('acl-view');
        return Permission::paginate(20);
    }

    public function getAllNotPaginate(){
        $this->checkAccess('acl-view');
        return Permission::all();
    }

    public function create($request){
        $this->checkAccess('acl-create');
    	//$dataRequest = $request->all();
        $permission = Permission::create($request);
        return $permission;
    }

    public function find($id){
        $this->checkAccess('acl-view');
        return Permission::findOrFail($id);
    }

    public function findByName($name){
        $this->checkAccess('acl-view');
    	$permissions = Permission::where('name', '=', $name)->get();
    	if(count($permissions) > 0)
    		return $permissions[0];
    	
		return null;
    }

    public function search($option, $value) {
        $this->checkAccess('acl-view');
        $permissions = Permission::where($option,'LIKE',"%{$value}%")->paginate(20);
        return $permissions;
    }

    public function update(PermissionUpdateRequest $request, $id){
        $this->checkAccess('acl-update');
        $permission = $this->find($id);
        $dataRequest = $request->all();
        $permission->update($dataRequest);
        return $permission;
    }

    public function delete($id){
        $this->checkAccess('acl-delete');
        $permission = $this->find($id);
        $permission->delete();
        return $permission;
    }
}