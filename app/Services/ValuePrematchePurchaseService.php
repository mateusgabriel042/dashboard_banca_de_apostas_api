<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Services\BetService;

class ValuePrematchePurchaseService {
	private $betService;

	public function __construct(){
		$this->betService = new BetService();
	}

	

    public function fullTimeResult($bets, $valuePurchase){
    	$listOdds = [];
    	foreach ($bets as $key => $item) {

    		$matche = $this->betService->getOddsMatcheInCache($item['id_league'], $item['id_matche']);

    		if($item['type_bet'] == 'full_time_result_of_matche'){
    			if($item['bet'] == 'home'){
    				array_push($listOdds, (double) $matche['odds']->main->sp->full_time_result->odds[0]->odds);
    			}else if($item['bet'] == 'away'){
    				array_push($listOdds, (double) $matche['odds']->main->sp->full_time_result->odds[2]->odds);
    			}else{
    				array_push($listOdds, (double) $matche['odds']->main->sp->full_time_result->odds[1]->odds);
    			}
    		}
    	}

    	$result = array_product($listOdds)*$valuePurchase;
    	return $result;
    }
}