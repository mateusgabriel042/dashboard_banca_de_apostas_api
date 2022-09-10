<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;
use App\Models\Country;
use App\Models\League;

class BetCacheService {
    private $baseURL = 'https://api.b365api.com/';
    private $URL_LEAGUES, $URL_ODDS, $URL_NEXT_MATCHES, $URL_MATCHES_LIVE, $URL_ODDS_MATCHES_LIVE;
    private $token = '132770-AqxaUAa4OY8EEj';
    private $qtdDaysSearch = 4;
    private $odds = [];
    private $client;

    public function __construct(){
        $this->client = new Client();
        $this->URL_LEAGUES = $this->baseURL.'v1/league';
        $this->URL_ODDS = $this->baseURL.'v3/bet365/prematch';
        $this->URL_NEXT_MATCHES = $this->baseURL.'v3/events/upcoming';
        $this->URL_MATCHES_LIVE = $this->baseURL.'v1/bet365/inplay_filter';
        $this->URL_ODDS_MATCHES_LIVE = $this->baseURL.'v1/bet365/event';
    }

    public function getMatchesLive(){
        $params = [
           'query' => [
                'token' => $this->token,
                'sport_id' => 1,
                'LNG_ID' => 22,
                'page' => 1,
           ]
        ];

        $response = $this->client->request('GET', $this->URL_MATCHES_LIVE, $params);

        $responseBody = json_decode($response->getBody());

        $matches = $responseBody->results;

        return $matches;
    }

    public function addOddsMatcheLiveInCache(){
        $listMatchsOddsLive = [];

        $matches = $this->getMatchesLive();

        foreach ($matches as $key => $item) {
            $params = [
               'query' => [
                    'token' => $this->token,
                    'FI' => $item->id,
                    'LNG_ID' => 22,
               ]
            ];

            $response = $this->client->request('GET', $this->URL_ODDS_MATCHES_LIVE, $params);

            $responseBody = json_decode($response->getBody());

            array_push($listMatchsOddsLive, [
                'matche' => $item,
                'odds' => $responseBody->results,
            ]);
        }

        Cache::forever('oddsMatchelives', $listMatchsOddsLive);

        $matchesInlivesOfCache = Cache::get('lives', []);

        return $matchesInlivesOfCache;
    }

    public function addLeaguesInCache(){
        set_time_limit(4000);
        $countries = Country::all();
        $listLeagues = [];

        foreach ($countries as $key => $item) {
            array_push($listLeagues, [
                'cc' => $item['sigle'],
                'country' => $item['label'],
                'leagues' => [],
            ]);
        }
        
        array_push($listLeagues, [
            'cc' => null,
            'country' => 'Outros',
            'leagues' => [],
        ]);

        foreach ($listLeagues as $key => $item) {
            $params = [
               'query' => [
                    'token' => $this->token,
                    'sport_id' => 1,
                    'page' => 1,
                    'LNG_ID' => 22,
                    'cc' => $item['cc'],//busca pela sigla do pais
               ]
            ];

            $response = $this->client->request('GET', $this->URL_LEAGUES, $params);

            $responseBody = json_decode($response->getBody());

            $listLeagues[$key]['leagues'] = $responseBody->results;
        }

        Cache::forever('leagues', $listLeagues);

        $listOfCache = Cache::get('leagues', []);

        return $listOfCache;
    }

    public function getOdds($eventsList){
        $idsEventsOdds = '';

        foreach ($eventsList as $key => $item) {
            if(property_exists($item, "bet365_id")){
                $idsEventsOdds .= $item->bet365_id.',';
            }
        }

        if($idsEventsOdds != ''){
            $params = [
               'query' => [
                    'token' => $this->token,
                    'FI' => $idsEventsOdds,
               ]
            ];

            $response = $this->client->request('GET', $this->URL_ODDS, $params);
            
            $responseBody = json_decode($response->getBody());

            $this->odds = $responseBody->results;
        }
    }

    public function findOddsByIdEvent($idEvent){
        foreach ($this->odds as $key => $item) {
            if($item->event_id == $idEvent){
                return $item;
            }
        }
        return [];
    }

    public function addMachesInCache(){
    	setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
        set_time_limit(4000);

        $countries = Country::where('is_active', '=', true)->get();

        foreach ($countries as $key => $country) {
            $leagues = League::where('country_id', '=', $country->id)
                             ->where('is_active', '=', true)
                             ->get();
            foreach ($leagues as $key => $league) {
                $listMatchesByLeague[$league->league_id] = [
                    'league' => $league->league_id,
                    'matchesByDay' => [],
                ];
            }
        }
        
        $matchesByDay = [];

        foreach ($listMatchesByLeague as $keyMatcheByLeague => $itemMacthByLeague) {
        
	        //looping que busca pela quantidade de dias informado
	        for($i = 0; $i < $this->qtdDaysSearch; $i++){
	            $params = [
	               'query' => [
	                    'token' => $this->token,
	                    'sport_id' => 1,
	                    'league_id' => $itemMacthByLeague['league'],
	                    'day' => date('Ymd', strtotime(' +'.$i.' day')),
	               ]
	            ];

	            $response = $this->client->request('GET', $this->URL_NEXT_MATCHES, $params);
	            
	            $responseBody = json_decode($response->getBody());

	            $this->getOdds($responseBody->results);

	            //adiconando data na lista de "jogos por dia"
	            foreach ($responseBody->results as $key => $item) {
	                $epoch = $item->time;
	                $dt = date('r', $epoch);
	                $day = utf8_encode(strftime('%A, %d de %B de %Y', strtotime($dt)));
	                $dayLabel = '';
	                if($day == utf8_encode(strftime('%A, %d de %B de %Y', strtotime('today')))){
	                    $dayLabel = 'Hoje';
	                }else if ($day == utf8_encode(strftime('%A, %d de %B de %Y', strtotime('+1 day', strtotime('today'))))){
	                    $dayLabel = 'Amanhã';
	                }else{
	                    $dayLabel = $day;
	                }

	                if(!collect($matchesByDay)->contains('day', $day)){
	                    array_push($matchesByDay, [
	                        'day' => $day,
	                        'dayLabel' => $dayLabel,
	                        'matches' => [],
	                    ]);
	                }
	                
	            }


	            //adiconando os jogos na lista de "jogos por dia"
	            foreach ($responseBody->results as $key => $item) {
	                $epoch = $item->time;
	                $dt = date('r', $epoch);
	                $day = utf8_encode(strftime('%A, %d de %B de %Y', strtotime($dt)));
	                foreach ($matchesByDay as $key => $subitem) {
	                    if($subitem['day'] == $day){
	                        array_push($matchesByDay[$key]['matches'], [
	                            'match' => $item,
	                            'odds' => $this->findOddsByIdEvent($item->id),
	                        ]);
	                    }
	                }
	            }
	        }

	        $listMatchesByLeague[$keyMatcheByLeague]['matchesByDay'] = $matchesByDay;

	        $matchesByDay = [];

        }

        Cache::forever('listMatchesByLeague', $listMatchesByLeague);

        $listMatchesByLeagueOfCache = Cache::get('listMatchesByLeague', []);

        return $listMatchesByLeagueOfCache;
    }

}