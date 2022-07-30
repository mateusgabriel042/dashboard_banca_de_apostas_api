<?php

namespace App\Http\Controllers\Bets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BetService;

class LeagueController extends Controller {
    private $betService;

    public function __construct(BetService $betService){
        $this->betService = $betService;
    }

    public function leagues(){
        return $this->betService->getLeaguesInCache();
    }

    public function matchsLeague($leagueId){
        return $this->betService->getMachesInCache($leagueId);
    }
}
