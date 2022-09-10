<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Services\BetCacheService;
use App\Models\Country;
use App\Models\League;

class BetService {
    private $odds = [];
    private $betCacheService;

    public function __construct(){
        $this->betCacheService = new BetCacheService();
    }

    public function getOddsMatchesLiveInCache(){
        $oddsMatchesInlivesOfCacheTemp = Cache::get('oddsMatchelives', []);

        if(count($oddsMatchesInlivesOfCacheTemp) == 0){
            return $this->betCacheService->addOddsMatcheLiveInCache();
        }

        $oddsMatchesInlivesOfCacheFinal = [];
        foreach ($oddsMatchesInlivesOfCacheTemp as $key => $item) {

            //verificando se a liga ja existe no array 
            if(in_array($item['matche']->league->name, array_column($oddsMatchesInlivesOfCacheFinal, 'league'))){
                //caso exista adiciona a partida no array de partidas da liga
                $arrayIndex = array_search($item['matche']->league->name, array_column($oddsMatchesInlivesOfCacheFinal, 'league'));
                array_push($oddsMatchesInlivesOfCacheFinal[$arrayIndex]['matches'], $item);
            }else{
                //caso nao exista adiciona a liga
                array_push($oddsMatchesInlivesOfCacheFinal,
                    [
                        'league' => $item['matche']->league->name,
                        'matches' => [
                            $item
                        ]
                    ]
                );
            }
        }

        return $oddsMatchesInlivesOfCacheFinal;
    }


    public function getOddsMatcheLiveInCache($idMatche){
        $oddsMatchesInlivesOfCache = Cache::get('oddsMatchelives', []);

        if(count($oddsMatchesInlivesOfCache) == 0){
            return [];
        }

        foreach ($oddsMatchesInlivesOfCache as $key => $item) {
            if($item['matche']->id == $idMatche){
                return $item;
            }
        }
        
        return [];
    }


    public function getLeaguesInCache(){
        $listLeaguesOfCache = Cache::get('leagues', []);

        if(count($listLeaguesOfCache) == 0){
        	return $this->betCacheService->addLeaguesInCache();
        }
        $finalListResults = [];

        $countries = Country::where('is_active', '=', true)->get();

        foreach ($listLeaguesOfCache as $key => $item) {
            if($countries->contains('sigle', $item['cc'])){
                $country = Country::where('sigle', '=', $item['cc'])->first();
                $leaguesActive = League::where('country_id', '=', $country->id)
                             ->where('is_active', '=', true)
                             ->get();

                $leaguesFinal = [];
                foreach ($item['leagues'] as $key => $league) {
                    if($leaguesActive->contains('league_id', $league->id)){
                        array_push($leaguesFinal, $league);
                    }
                }
                $item['leagues'] = $leaguesFinal;
                array_push($finalListResults, $item);

            }
        }
        

        return $finalListResults;   
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