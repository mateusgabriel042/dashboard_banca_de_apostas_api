<?php

namespace App\Http\Controllers\Sports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Sports\OddPrematcheService as SportsOddPrematcheService;
use App\Traits\ApiResponser;

class OddPrematcheController extends Controller
{
    use ApiResponser;

	private $sportOddPrematcheService;
	
    public function __construct(SportsOddPrematcheService $sportOddPrematcheService){
		$this->sportOddPrematcheService = $sportOddPrematcheService;
	}

	public function registerOdds() {
        try {
            $oddsPrematcheRegistered = $this->sportOddPrematcheService->registerOdds();
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        return $this->success([
            'oddsPrematcheRegistered' => $oddsPrematcheRegistered,
        ],  'Registro realizado com sucesso!');
    }
}
