<?php

namespace App\Services\Sports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Sport;
use App\Models\Matche;
use App\Models\League;
use App\Models\Country;
use GuzzleHttp\Client;


class MatcheService {
	private $client;

    public function __construct(){
    	$this->client = new Client();
    }
		

	public function registerMatches(){
		set_time_limit(12000);
		$token = '128924-yo5oBbhetblA2z';
    	$sports = Sport::where('is_active', '=', 1)->get();
        $leagues = League::where('is_active', '=', 1)->get();
        $page = 1;
        $url = 'https://api.b365api.com/v3/events/upcoming';
        $days = date('Ymd').','.date('Ymd', strtotime('+1 days')).','.date('Ymd', strtotime('+2 days'));
        date_default_timezone_set('America/Sao_Paulo');
        

        foreach ($sports as $key => $sport) {
    		foreach ($leagues as $key => $league) {
    			$response = $this->client->request('get', $url, [
		            'query' => [
		            	'token' => $token,
			            'sport_id' => $sport->apievents_id,
			            'league_id' => $league->apievents_league_id,
			            'page' => $page,
			            'LNG_ID' => 22, //lingua pb-BR
			        ],
		        ]);
		        $responseBody = json_decode($response->getBody())->results;
		        foreach ($responseBody as $key => $matche) {

			        Matche::create([
						'bet365_matche_id' => $matche->bet365_id,
				        'apievents_sport_id' => $sport->apievents_id,
				        'apievents_league_id' => $league->apievents_league_id,
				        'team_home_id' => $matche->home->id,
				        'team_away_id' => $matche->away->id,
				        'team_home_name' => $matche->home->name,
				        'team_away_name' => $matche->away->name,
				        'team_home_image_id' => $matche->home->image_id,
				        'team_away_image_id' => $matche->away->image_id,
				        'date_matche' => date('Y-m-d H:i:s', $matche->time),
				        'object_odds_prematche' => null,
				        'sport_name' => $sport->name,
				        'sport_label' => $sport->label,
				        'league_id' => $league->id,
				        'country_id' => $league->country_id,
					]);
		        }
    		}
        }
	}
}