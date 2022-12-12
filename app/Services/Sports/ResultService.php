<?php

namespace App\Services\Sports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Sport;
use App\Models\Matche;
use App\Models\ResultSoccer;
use GuzzleHttp\Client;


class ResultService {
	private $client;

    public function __construct(){
    	$this->client = new Client();
    }
		
	public function registerResultsSoccer(){
		set_time_limit(12000);
		date_default_timezone_set('America/Sao_Paulo');

		$token = '128924-yo5oBbhetblA2z';
    	$sport = Sport::where('apievents_id', '=', 1)//futebol
    				  ->where('is_active', '=', 1)
    				  ->first();
    	

    	if($sport != null){
	        $url = 'https://api.b365api.com/v1/bet365/result';

	        $matches = Matche::where('apievents_sport_id', '=', 1)
    					 ->where('object_odds_prematche', '!=', null)
    					 ->get();

    		$groupIdsMatches = $this->groupIdsMatches($matches);
	        
			foreach ($groupIdsMatches as $key => $ids) {
				$response = $this->client->request('get', $url, [
		            'query' => [
		            	'token' => $token,
			            'event_id' => $ids,
			            'LNG_ID' => 22, //lingua pb-BR
			        ],
		        ]);
		        $resultsMatchesSoccer = json_decode($response->getBody(), true)['results'];
		        foreach ($resultsMatchesSoccer as $key => $result) {
			        ResultSoccer::create([
			        	'score' => $result['ss'],
				        'time_status' => $result['time_status'],
				        'home_goals_first_time' => $result['scores'][1]['home'],
				        'away_goals_first_time' => $result['scores'][1]['away'],
				        'home_goals_second_time' => $result['scores'][2]['home'],
				        'away_goals_second_time' => $result['scores'][2]['away'],
				        'home_attacks' => $result['stats']['attacks'][0],
				        'away_attacks' => $result['stats']['attacks'][1],
				        'home_ball_safe' => $result['stats']['ball_safe'][0],
				        'away_ball_safe' => $result['stats']['ball_safe'][1],
				        'home_corners' => $result['stats']['corners'][0],
				        'away_corners' => $result['stats']['corners'][1],
				        'home_corner_h' => $result['stats']['corner_h'][0],
				        'away_corner_h' => $result['stats']['corner_h'][1],
				        'home_dangerous_attacks' => $result['stats']['dangerous_attacks'][0],
				        'away_dangerous_attacks' => $result['stats']['dangerous_attacks'][1],
				        'home_fouls' => $result['stats']['fouls'][0],
				        'away_fouls' => $result['stats']['fouls'][1],
				        'home_freekicks' => $result['stats']['freekicks'][0],
				        'away_freekicks' => $result['stats']['freekicks'][1],
				        'home_goalattempts' => $result['stats']['goalattempts'][0],
				        'away_goalattempts' => $result['stats']['goalattempts'][1],
				        'home_goalkicks' => $result['stats']['goalkicks'][0],
				        'away_goalkicks' => $result['stats']['goalkicks'][1],
				        'home_goals' => $result['stats']['goals'][0],
				        'away_goals' => $result['stats']['goals'][1],
				        'home_injuries' => isset($result['stats']['injuries']) ? $result['stats']['injuries'][0] : null,
				        'away_injuries' => isset($result['stats']['injuries']) ? $result['stats']['injuries'][1] : null,
				        'home_offsides' => isset($result['stats']['offsides']) ? $result['stats']['offsides'][0] : null,
				        'away_offsides' => isset($result['stats']['offsides']) ? $result['stats']['offsides'][1] : null,
				        'home_off_target' => $result['stats']['off_target'][0],
				        'away_off_target' => $result['stats']['off_target'][1],
				        'home_on_target' => $result['stats']['on_target'][0],
				        'away_on_target' => $result['stats']['on_target'][1],
				        'home_penalties' => $result['stats']['penalties'][0],
				        'away_penalties' => $result['stats']['penalties'][1],
				        'home_possession_rt' => $result['stats']['possession_rt'][0],
				        'away_possession_rt' => $result['stats']['possession_rt'][1],
				        'home_redcards' => $result['stats']['redcards'][0],
				        'away_redcards' => $result['stats']['redcards'][1],
				        'home_saves' => $result['stats']['saves'][0],
				        'away_saves' => $result['stats']['saves'][1],
				        'home_shots_blocked' => $result['stats']['shots_blocked'][0],
				        'away_shots_blocked' => $result['stats']['shots_blocked'][1],
				        'home_substitutions' => $result['stats']['substitutions'][0],
				        'away_substitutions' => $result['stats']['substitutions'][1],
				        'home_throwins' => $result['stats']['throwins'][0],
				        'away_throwins' => $result['stats']['throwins'][1],
				        'home_yellowcards' => $result['stats']['yellowcards'][0],
				        'away_yellowcards' => $result['stats']['yellowcards'][1],
				        'home_yellowred_cards' => $result['stats']['yellowred_cards'][0],
				        'away_yellowred_cards' => $result['stats']['yellowred_cards'][1],
				        'matche_id' => $matches->where('bet365_matche_id', '=', $result['bet365_id'])->first()->id,
			        ]);
		        }
		        
			}

		}
	}

	public function isUndefined($bool, $index){
		if($bool)
			return $obj[$index];
		else
			return null;

	}

	public function groupIdsMatches($matches){
        $arrayIdsMatche = [];
        $ids = '';
        foreach ($matches as $key => $matche) {
            if(($key+1)%10 != 0){
                if($key != $matches->count()-1){
                    $ids.=$matche->bet365_matche_id.',';
                }else{
                    $ids.=$matche->bet365_matche_id;
                    array_push($arrayIdsMatche, $ids);
                    $ids = '';
                }
            }else{
                $ids.=$matche->bet365_matche_id;
                array_push($arrayIdsMatche, $ids);
                $ids = '';
            }
        }

        return $arrayIdsMatche;
    }
}