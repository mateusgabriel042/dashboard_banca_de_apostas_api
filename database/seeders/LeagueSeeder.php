<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use GuzzleHttp\Client;
use App\Models\Country;
use App\Models\Sport;
use App\Models\League;

class LeagueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	set_time_limit(4000);
    	$client = new Client();
    	$token = '128924-yo5oBbhetblA2z';
    	$sports = Sport::where('is_active', '=', 1)->get();
        $url = 'https://api.b365api.com/v1/league';
        $qtdPages = 24;

        foreach ($sports as $key => $sport) {

        	for($page = 1; $page <= $qtdPages; $page++){
		        $response = $client->request('get', $url, [
		            'query' => [
		            	'token' => $token,
			            'sport_id' => $sport['apievents_id'],
			            'page' => $page,
			            'LNG_ID' => 22, //lingua pb-BR
			        ],
		        ]);
		        $responseBody = json_decode($response->getBody())->results;

		        foreach ($responseBody as $key => $league) {

		        	$country = Country::where('code', '=', $league->cc)->first();
		        	if($country != null){
		        		if($country['id'] != null){
							League::create([
								'sport_name' => $sport['name'],
						        'sport_label' => $sport['label'],
						        'apievents_sport_id' => $sport['apievents_id'],
						        'apievents_league_id' => $league->id,
						        'name' => $league->name,
						        'label_name' => $league->name,
						        'type' => 'cup',
						        'label_type' => 'copa',
						        'logo' => null,
						        'is_active' => 0,
						        'sport_id' => $sport['id'],
						        'country_id' => $country['id'],
							]);
						}
					}
				}
			}
		}

    }
}
