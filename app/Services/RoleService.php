<?php

namespace App\Services;

use App\Models\Role;
use App\Http\Requests\RoleRegisterRequest;
use App\Http\Requests\RoleUpdateRequest;
use Illuminate\Support\Facades\DB;
use App\Traits\CheckAccess;

class RoleService {
    use CheckAccess;

    public function getAll(){
        $this->checkAccess('acl-view');
        return Role::with('permissions')->paginate(20);
    }

    public function getAllNotPaginate(){
        $this->checkAccess('acl-view');
        return Role::all();
    }

    public function create(RoleRegisterRequest $request){
        $this->checkAccess('acl-create');
    	$dataRequest = $request->all();
        $role = Role::create($dataRequest);
        return $role;
    }

    public static function createRoleIfNotExist($role, $slug){
        $roleSelected = Role::where('name', '=', $role)->first();

        if($roleSelected == null){
            $role = Role::create([
                'name' => $role,
                'slug' => $slug,
            ]);

            return $role;
        }

        return $roleSelected;
    }

    public function find($id){ 
        $this->checkAccess('acl-view');
        return Role::with('permissions')->findOrFail($id);
    }

    public function search($option, $value) {
        $this->checkAccess('acl-view');
        $roles = Role::with(['permissions'])->where($option,'LIKE',"%{$value}%")->paginate(20);
        return $roles;
    }

    public function update(RoleUpdateRequest $request, $id){
        $this->checkAccess('acl-update');
        $role = $this->find($id);
        $dataRequest = $request->all();
        $role->update($dataRequest);
        return $role;
    }

    public function delete($id){
        $this->checkAccess('acl-delete');
        $role = $this->find($id);
        $role->permissions()->delete();
        $role->delete();
        return $role;
    }

    public function removePermission($idRole, $idPermission){
        $this->checkAccess('acl-delete');
        DB::connection('tenant')->table('roles_permissions')
          ->where('role_id', $idRole)
          ->where('permission_id', $idPermission)
          ->delete();
        $role = $this->find($idRole);
        return $role;
    }
}