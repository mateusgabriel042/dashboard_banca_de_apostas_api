<?php

namespace App\Http\Controllers\Sports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Sports\LeagueService as SportsLeagueService;
use App\Traits\ApiResponser;

class LeagueController extends Controller
{
	use ApiResponser;

	private $sportLeagueService;
	
    public function __construct(SportsLeagueService $sportLeagueService){
		$this->sportLeagueService = $sportLeagueService;
	}

    public function getLeaguesByCountryOfAPIOut($codeCountry, $sportName) {
    	try {
            $leaguesByCountryOfAPIOut = $this->sportLeagueService->getLeaguesByCountryOfAPIOut($codeCountry, $sportName);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        return $this->success([
            'leaguesByCountryOfAPIOut' => $leaguesByCountryOfAPIOut,
        ],  'Listagem realizada com sucesso!');
    }

    public function registerLeaguesDBOfAPIOut() {
        try {
            $leaguesRegisteredDBOfAPIOut = $this->sportLeagueService->registerLeaguesDBOfAPIOut();
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        return $this->success([
            'leaguesRegisteredDBOfAPIOut' => $leaguesRegisteredDBOfAPIOut,
        ],  'Listagem realizada com sucesso!');
    }
}
