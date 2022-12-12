<?php

namespace App\Http\Controllers\Bets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\BetPurchaseRegisterRequest as EndpointRegisterRequest;
use App\Http\Requests\BetPurchaseUpdateRequest as EndpointUpdateRequest;
use App\Http\Resources\BetPurchaseResource as EndpointResource;
use App\Http\Resources\BetPurchaseCollection as EndpointCollection;
use App\Traits\ApiResponser;
use App\Services\BetPurchaseService as EndpointService;
use App\Models\BetPurchase as ModelEndpoint;

class BetPurchaseController extends Controller
{
    use ApiResponser;

    private $endpointService;
    private $role = 'bet-purchase';
    private $endpointName = 'Aposta(s) adquirida(s)';
    private $relations = ['user', 'bets'];

    public function __construct(){
        $this->endpointService = new EndpointService($this->role, new ModelEndpoint());
    }
    
    public function index() {
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

    public function all() {
        try {
            $items = $this->endpointService->getAllNotPaginate($this->relations);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        return $this->success([
            'items' => new EndpointCollection($items),
        ],  'Listagem de '.$this->endpointName.' realizada com sucesso!');
    }

    public function search($option, $value) {
        $items = $this->endpointService->search($option, $value, $this->relations);

        return $this->success([
            'items' => new EndpointCollection($items),
            'pagination' => ['pages' => $items->lastPage()],
        ],  'Listagem de '.$this->endpointName.' realizada com sucesso!');
    }

    public function store(EndpointRegisterRequest $request) {
        if (isset($request->validator) && $request->validator->fails()) {
            return $this->error('Erro ao cadastrar o '.$this->endpointName, 422, [
                'errors' => $request->validator->messages(),
            ]);
        }

        try {
            $item = $this->endpointService->create($request);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        return $this->success([
            'item' => new EndpointResource($item),
        ],  $this->endpointName.' cadastrada com sucesso!');
    }

    public function show($id) {
        try {
            $item = $this->endpointService->find($id, $this->relations);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        return $this->success([
            'item' => new EndpointResource($item),
        ]);
    }

    public function update(EndpointUpdateRequest $request, $id) {
        try{
            $item = $this->endpointService->update($request, $id);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        return $this->success([
            'item' => new EndpointResource($item),
        ],  $this->endpointName.' atualizada(o) com sucesso!');
    }

    public function destroy($id) {
        try {
            $item = $this->endpointService->delete($id);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        return $this->success([
            'item' => new EndpointResource($item),
        ]);
    }
}
