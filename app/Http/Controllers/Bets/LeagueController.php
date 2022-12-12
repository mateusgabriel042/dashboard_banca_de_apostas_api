<?php

namespace App\Http\Controllers\Bets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Services\LeagueService;
use App\Models\League;

class LeagueController extends Controller {
    use ApiResponser;

    private $leagueService;
    private $role = 'league';
    private $endpointName = 'liga(s)';

    public function __construct(){
        $this->leagueService = new LeagueService($this->role, new League());
    }

    public function allCountryLeagues(){

        try {
            $items = $this->leagueService->allCountryLeagues();
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        return $this->success([
            'items' => $items,
        ],  'Listagem de ligas de cada país realizada com sucesso!');
    }

    public function allLeagueMatches($apieventsSportId, $apieventsLeagueId){

        try {
            $item = $this->leagueService->allLeagueMatches($apieventsSportId, $apieventsLeagueId);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        return $this->success([
            'item' => $item,
        ],  'Listagem de partidas da liga selecionada realizada com sucesso!');
    }

    

    /*public function matchsLeague($leagueId){
        return $this->betService->getMachesInCache($leagueId);
    }

    

    public function matche($leagueId, $matcheId){
        return $this->betService->getMatcheInCache($leagueId, $matcheId);
    }

    public function lives(){
        return $this->betService->getOddsMatchesLiveInCache();
    }

    public function live($matcheId){
        return $this->betService->getOddsMatcheLiveInCache($matcheId);
        
    }*/
}
