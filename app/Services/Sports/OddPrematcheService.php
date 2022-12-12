<?php

namespace App\Services\Sports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Sport;
use App\Models\Matche;
use App\Models\League;
use App\Models\Country;
use GuzzleHttp\Client;

class OddPrematcheService {
    private $client;
	public function __construct(){
        $this->client = new Client();
    }

    public function registerOdds(){
        set_time_limit(12000);
        $token = '128924-yo5oBbhetblA2z';
        $url = 'https://api.b365api.com/v3/bet365/prematch';

        $from = date('Y-m-d', strtotime(date('Y-m-d').' -1 days'));
        $to = date('Y-m-d', strtotime(date('Y-m-d').' +3 days'));

        $matches = Matche::whereBetween('date_matche', [$from, $to])->get();
        
        $groupIdsMatches = $this->groupIdsMatches($matches);

        foreach ($groupIdsMatches as $key => $ids) {
            
            $response = $this->client->request('get', $url, [
                'query' => [
                    'token' => $token,
                    'FI' => $ids,
                ],
            ]);
            $odds = json_decode($response->getBody())->results;

            foreach ($odds as $key => $odd) {
                $matche = Matche::where('bet365_matche_id', '=', $odd->FI)->first();
                $matche['object_odds_prematche'] = json_encode($odd);
                $matche->save();
            }
        }

        return $matches;

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