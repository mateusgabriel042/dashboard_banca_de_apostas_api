<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Services\UserService;

class DashboardController extends Controller {
	use ApiResponser;

    private $userService;

    public function __construct(UserService $userService){
    	$this->userService = $userService;
    }

    public function index(){
    	$userData = $this->userService->getData();
    	
        return $this->success([
            'usersData' => $userData,
        ],  'Listagem de dados do Dashboard realizada com sucesso!');
    }

    public function store(Request $request){}

    public function show($id){}

    public function update(Request $request, $id){}

    public function destroy($id){}
}
