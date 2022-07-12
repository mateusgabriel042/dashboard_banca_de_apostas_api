<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Http\Requests\RoleRegisterRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Http\Requests\PermissionUpdateRequest;
use App\Http\Resources\RoleResource;
use App\Http\Resources\RoleCollection;
use App\Traits\ApiResponser;
use App\Services\RoleService;
use App\Services\PermissionService;


class RoleController extends Controller {
    use ApiResponser;

    private $roleService;
    private $permissionService;

    public function __construct(RoleService $roleService, PermissionService $permissionService){
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
    }
    
    public function index() {
        try {
            $roles = $this->roleService->getAll();
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        return $this->success([
            'roles' => new RoleCollection($roles),
            'pagination' => ['pages' => $roles->lastPage()],
        ],  'Listagem de funções realizada com sucesso!');
    }

    public function all() {
        try {
            $roles = $this->roleService->getAllNotPaginate();
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        return $this->success([
            'roles' => new RoleCollection($roles),
        ],  'Listagem de funções realizada com sucesso!');
    }

    public function search($option, $value) {
        try{
            $roles = $this->roleService->search($option, $value);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        return $this->success([
            'roles' => new RoleCollection($roles),
            'pagination' => ['pages' => $roles->lastPage()],
        ],  'Listagem de funções realizada com sucesso!');
    }

    public function store(RoleRegisterRequest $request) {
        if (isset($request->validator) && $request->validator->fails()) {
            return $this->error('Erro ao cadastrar Função!', 422, [
                'errors' => $request->validator->messages(),
            ]);
        }
        
        try {
            $role = $this->roleService->create($request);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        $permissions = $request->input('permissions');

        foreach ($permissions as $item) {
            $permission = $this->permissionService->findByName($item['name']);
            
            if($permission == null)
                $permission = $this->permissionService->create($item);

            $role->permissions()->attach($permission->id);
        }

        return $this->success([
            'role' => new RoleResource($role),
        ]);
    }

    public function show($id) {
        try{
            $role = $this->roleService->find($id);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        return $this->success([
            'role' => new RoleResource($role),
        ]);
    }

    public function update(RoleUpdateRequest $roleRequest, $id) {
        try {
            $role = $this->roleService->update($roleRequest, $id);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        $role->permissions()->detach();
        $role->permissions()->delete();

        $permissions = $roleRequest->input('permissions');
        foreach ($permissions as $item) {
            $permission = $this->permissionService->findByName($item['name']);
            
            if($permission == null)
                $permission = $this->permissionService->create($item);

            $role->permissions()->attach($permission->id);
        }

        return $this->success([
            'role' => new RoleResource($role),
        ]);
    }

    public function destroy($id) {
        try {
            $role = $this->roleService->delete($id);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        $role->permissions()->detach();
        return $this->success([
            'role' => new RoleResource($role),
        ]);
    }

    public function removePermission($idRole, $idPermission){
        try {
            $role = $this->roleService->removePermission($idRole, $idPermission);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
        
        return $this->success([
            'role' => new RoleResource($role),
        ]);
    }
}
