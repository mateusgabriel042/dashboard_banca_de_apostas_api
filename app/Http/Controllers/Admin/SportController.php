<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SportRegisterRequest as EndpointRegisterRequest;
use App\Http\Requests\SportUpdateRequest as EndpointUpdateRequest;
use App\Http\Resources\SportResource as EndpointResource;
use App\Http\Resources\SportCollection as EndpointCollection;
use App\Traits\ApiResponser;
use App\Services\SportService as EndpointService;
use App\Models\Sport as ModelEndpoint;

class SportController extends Controller
{
	use ApiResponser;
	
	private $endpointService;
    private $role = 'sport';
    private $endpointName = 'sport(s)';
    private $relations = [];

    public function __construct(){
        $this->endpointService = new EndpointService($this->role, new ModelEndpoint());
    }

    public function index(){
    	try {
            $items = $this->endpointService->getAll($this->relations);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        return $this->success([
            'items' => new EndpointCollection($items),
            'pagination' => ['pages' => $items->lastPage()],
        ],  'Listagem de '.$this->endpointName.' realizada com sucesso!');
    }
}
