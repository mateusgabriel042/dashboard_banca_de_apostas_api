<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;
use App\Services\BetCacheService;

class BetService {
    private $baseURL = 'https://api.b365api.com/';
    private $URL_LEAGUES, $URL_ODDS, $URL_NEXT_MATCHES;
    private $token = '102639-xbQIQN1i29iMtD';
    private $qtdDaysSearch = 4;
    private $odds = [];
    private $client;
    private $betCacheService;

    public function __construct(BetCacheService $betCacheService){
        $this->client = new Client();
        $this->betCacheService = $betCacheService;
        $this->URL_LEAGUES = $this->baseURL.'v1/league';
        $this->URL_ODDS = $this->baseURL.'v3/bet365/prematch';
        $this->URL_NEXT_MATCHES = $this->baseURL.'v3/events/upcoming';
    }

    public function getLeaguesInCache(){
        
        $listLeaguesOfCache = Cache::get('leagues', []);

        if(count($listLeaguesOfCache) == 0){
        	return $this->betCacheService->addLeaguesInCache();
        }

        return $listLeaguesOfCache;   
    }


    public function getMachesInCache($leagueId){
        
        $listMatchesByLeagueOfCache = Cache::get('listMatchesByLeague', []);

        if(count($listMatchesByLeagueOfCache) == 0){
        	$listMatchesByLeagueOfCache = $this->betCacheService->addMachesInCache();
        }

        return $listMatchesByLeagueOfCache[$leagueId];
    }

    public function getOddsMatcheInCache($leagueId, $matcheId){
        $listMatchesByLeagueOfCache = Cache::get('listMatchesByLeague', []);

        if(count($listMatchesByLeagueOfCache) == 0){
            $listMatchesByLeagueOfCache = $this->betCacheService->addMachesInCache();
        }

        foreach ($listMatchesByLeagueOfCache[$leagueId]['matchesByDay'] as $key => $matchsByDay) {
            foreach ($matchsByDay['matches'] as $key => $item) {
                if($item['match']->id == $matcheId){
                    return $item;
                }
            }
        }

        return [];
    }

    public function getMatcheInCache($leagueId, $matcheId){
        $listMatchesByLeagueOfCache = Cache::get('listMatchesByLeague', []);

        if(count($listMatchesByLeagueOfCache) == 0){
            $listMatchesByLeagueOfCache = $this->betCacheService->addMachesInCache();
        }

        foreach ($listMatchesByLeagueOfCache[$leagueId]['matchesByDay'] as $key => $matchsByDay) {
            foreach ($matchsByDay['matches'] as $key => $item) {
                if($item['match']->id == $matcheId){
                    return $item['match'];
                }
            }
        }

        return [];
    }

}