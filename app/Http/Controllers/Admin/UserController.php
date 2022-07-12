<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Traits\ApiResponser;
use App\Services\UserService;

class UserController extends Controller {
    use ApiResponser;

    private $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }
    
    public function index() {
        try {
            $users = $this->userService->getAll();
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        return $this->success([
            'users' => new UserCollection($users),
            'pagination' => ['pages' => $users->lastPage()],
        ],  'Listagem de usuários realizada com sucesso!');
    }

    public function all() {
        try {
            $users = $this->userService->getAllNotPaginate();
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        return $this->success([
            'users' => new UserCollection($users),
        ],  'Listagem de usuários realizada com sucesso!');
    }

    public function search($option, $value) {
        $users = $this->userService->search($option, $value);

        return $this->success([
            'users' => new UserCollection($users),
            'pagination' => ['pages' => $users->lastPage()],
        ],  'Listagem de usuários realizada com sucesso!');
    }

    public function store(UserRegisterRequest $request) {
        if (isset($request->validator) && $request->validator->fails()) {
            return $this->error('Erro ao cadastrar o usuário!', 422, [
                'errors' => $request->validator->messages(),
            ]);
        }

        try {
            $user = $this->userService->create($request);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        $role = $request->input('role_id');//id of role

        if($role != null){
            $user->roles()->attach($role);
            $user->save();
        }

        $permissions = $request->input('permissions');//id of permissions

        if($permissions != null){
            foreach ($permissions as $item) {
                $user->permissions()->attach($item['id_permission']);
            }
        }

        return $this->success([
            'user' => new UserResource($user),
        ]);
    }

    public function show($id) {
        try {
            $user = $this->userService->find($id);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        return $this->success([
            'user' => new UserResource($user),
        ]);
    }

    public function update(UserUpdateRequest $request, $id) {
        if (isset($request->validator) && $request->validator->fails()) {
            return $this->error('Erro ao atualizar o usuário!', 422, [
                'errors' => $request->validator->messages(),
            ]);
        }
        
        try {
            $user = $this->userService->update($request, $id);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        $role = $request->input('role_id');//id of role

        if($role != null ){
            $user->roles()->attach($role);
        }

        $permissions = $request->input('permissions');//id of permissions

        if($permissions != null){
            foreach ($permissions as $item) {
                $user->permissions()->attach($item['id_permission']);
            }
        }

        return $this->success([
            'user' => new UserResource($user),
        ]);
    }

    public function destroy($id) {
        try {
            $user = $this->userService->delete($id);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        return $this->success([
            'user' => new UserResource($user),
        ]);
    }

    public function removeRole($idUser, $idRole){
        try {
            $user = $this->userService->removeRole($idUser, $idRole);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
        return $this->success([
            'user' => new UserResource($user),
        ]);
    }

    public function removePermission($idUser, $idPermission){
        try {
            $user = $this->userService->removePermission($idUser, $idPermission);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
        return $this->success([
            'user' => new UserResource($user),
        ]);
    }
}
