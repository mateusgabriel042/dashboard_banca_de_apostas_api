<?php

namespace App\Http\Controllers\Sports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Sports\ResultService;
use App\Traits\ApiResponser;

class ResultController extends Controller
{
    use ApiResponser;

	private $resultService;
	
    public function __construct(ResultService $resultService){
		$this->resultService = $resultService;
	}

	public function registerResultsSoccer() {
        try {
            $resultsRegistered = $this->resultService->registerResultsSoccer();
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        return $this->success([
            'resultsRegistered' => $resultsRegistered,
        ],  'Registro de resultados realizado com sucesso!');
    }
}
