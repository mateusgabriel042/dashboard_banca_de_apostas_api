<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Services\RoleService;
use App\Services\PermissionService;
use App\Traits\CheckAccess;

class UserService {
    use CheckAccess;

    private $permissionService;
    private $roleService;

    public function __construct(PermissionService $permissionService, RoleService $roleService){
        $this->permissionService = $permissionService;
        $this->roleService = $roleService;
    }

    public function getData(){
        $data = [
            'count' => User::all()->count(),
        ];
        return $data;
    }

    public function getAll(){
        $this->checkAccess('user-view');
        return User::with(['role', 'permissions'])->paginate(20);
    }

    public function getAllNotPaginate(){
        $this->checkAccess('user-view');
        return User::orderBy('name','ASC')->get();
    }

    public function create(UserRegisterRequest $request){
    	$dataRequest = $request->all();
        $dataRequest['password'] = Hash::make($dataRequest['password']);
        $user = User::create($dataRequest);
        return $user;
    }

    public function createUserLocalByToten($request){
        $dataUserLocal['name'] = $request['name'];
        $dataUserLocal['email'] = $request['email'];
        $dataUserLocal['password'] = Hash::make($request['password']);
        $user = User::create($dataUserLocal);

        return $user;
    }

    public static function relationUserRoleExist($id1, $id2){
        $relationExist = 
            DB::table('users_roles')
              ->where('user_id', $id1)
              ->where('role_id', $id2)->first() != null ? true : false;

        return $relationExist;
    }

    public static function createUserIfNotExist($name, $email, $password){
        $user = User::where('name', '=', $name)->first();

        if($user == null){
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);
        }

        return $user;
    }

    public function find($id){
        return User::with(['role', 'permissions'])->findOrFail($id);
    }

    public function search($option, $value) {
        $this->checkAccess('user-view');
        $users = User::with(['role', 'permissions'])->where($option,'LIKE',"%{$value}%")->paginate(20);
        return $users;
    }

    public function update(UserUpdateRequest $request, $id){
        $this->checkAccess('user-update');
        $user = User::findOrFail($id);
        $dataUser = $request->except('role_id', 'permissions');
        if(isset($dataUser['password']) != null){
            $dataUser['password'] = Hash::make($dataUser['password']);
        }
        $user->update($dataUser);

        $user->roles()->detach();
        $user->permissions()->detach();

        return $user;
    }

    public function delete($id){
        $this->checkAccess('user-delete');
        $user = $this->find($id);
        $user->roles()->detach();
        $user->permissions()->detach();
        $user->delete();
        return $user;
    }

    public function removeRole($idUser, $idRole){
        $this->checkAccess('user-update');
        DB::connection('tenant')->table('users_roles')
          ->where('user_id', $idUser)
          ->where('role_id', $idRole)
          ->delete();
        $user = $this->find($idUser);
        return $user;
    }

    public function removePermission($idUser, $idPermission){
        $this->checkAccess('user-update');
        DB::connection('tenant')->table('users_permissions')
          ->where('user_id', $idUser)
          ->where('permission_id', $idPermission)
          ->delete();
        $user = $this->find($idUser);
        return $user;
    }
}