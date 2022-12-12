<?php

namespace App\Http\Controllers\Sports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Sports\MatcheService as SportsMatcheService;
use App\Traits\ApiResponser;

class MatcheController extends Controller
{
    use ApiResponser;

	private $sportMatcheService;
	
    public function __construct(SportsMatcheService $sportMatcheService){
		$this->sportMatcheService = $sportMatcheService;
	}

	public function registerMatches() {
        try {
            $matchesRegistered = $this->sportMatcheService->registerMatches();
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        return $this->success([
            'matchesRegistered' => $matchesRegistered,
        ],  'Registro realizado com sucesso!');
    }
}
