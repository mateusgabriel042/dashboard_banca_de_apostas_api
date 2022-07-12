<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Services\PermissionService;
use App\Http\Requests\PermissionRegisterRequest;
use App\Http\Requests\PermissionUpdateRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\PermissionCollection;

class PermissionController extends Controller {
    use ApiResponser;

    private $permissionService;

    public function __construct(PermissionService $permissionService){
        $this->permissionService = $permissionService;
    }
    
    public function index() {
        $permissions = $this->permissionService->getAll();

        return $this->success([
            'permissions' => new PermissionCollection($permissions),
            'pagination' => ['pages' => $permissions->lastPage()],
        ],  'Listagem de permissões realizada com sucesso!');
    }

    public function all() {
        $permissions = $this->permissionService->getAllNotPaginate();

        return $this->success([
            'permissions' => new PermissionCollection($permissions),
        ],  'Listagem de permissões realizada com sucesso!');
    }

    public function search($option, $value) {
        $permissions = $this->permissionService->search($option, $value);

        return $this->success([
            'permissions' => new PermissionCollection($permissions),
            'pagination' => ['pages' => $permissions->lastPage()],
        ],  'Listagem de permissões realizada com sucesso!');
    }

    public function store(PermissionRegisterRequest $request) {}

    public function show($id) {
        $permission = $this->permissionService->find($id);

        return $this->success([
            'permission' => new PermissionResource($permission),
        ]);
    }

    public function update(PermissionUpdateRequest $request, $id) {}

    public function destroy($id) {}
}
