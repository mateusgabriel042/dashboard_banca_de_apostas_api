<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Services\UserService;
use Carbon\Carbon;

class AuthController extends Controller {
	use ApiResponser;

    private $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    public function register(UserRegisterRequest $request) {
        if (isset($request->validator) && $request->validator->fails()) {
            return $this->error('Erro ao realizar o cadastro!', 422, [
                'errors' => $request->validator->messages(),
            ]);
        }

    	$dataUser = $request->except('money');
        $dataUser['birth_date'] = Carbon::createFromFormat('d/m/Y', $dataUser['birth_date'])->format('Y-m-d');
    	$dataUser['password'] = Hash::make($dataUser['password']);
        $user = User::create($dataUser);

        return $this->success([
        	'user' => $user,
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

    public function update(UserUpdateRequest $request, $id){
        $user = User::find($id);
        $dataUser = $request->except('role_id', 'permissions', 'money');
        $dataUser['birth_date'] = Carbon::createFromFormat('d/m/Y', $dataUser['birth_date'])->format('Y-m-d');
        if(isset($dataUser['password']) != null){
            $dataUser['password'] = Hash::make($dataUser['password']);
        }
        $user->update($dataUser);

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

    public function login(UserLoginRequest $request) {
        if (isset($request->validator) && $request->validator->fails()) {
            return $this->error('Erro ao fazer login!', 422, [
                'errors' => $request->validator->messages(),
            ]);
        }

    	$dataUser = $request->all();
        
        if (!Auth::attempt($dataUser)) {
            return $this->error('Credenciais não encontradas.', 401);
        }

        $user = $this->userService->find(auth()->user()->id);

        return $this->success([
        	'user'  => $user,
            'token' => $user->createToken('API Token')->plainTextToken,
        ]);
    }

    public function logout(){	
        return [
            'message' => 'Token deletado.'
        ];
    }
}
