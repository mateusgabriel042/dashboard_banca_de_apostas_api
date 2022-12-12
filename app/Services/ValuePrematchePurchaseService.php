<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Services\BetService;

class ValuePrematchePurchaseService {
	private $betService;

	public function __construct(){
		$this->betService = new BetService();
	}

	

    public function getReturnBet($bets, $valuePurchase){
    	$listOdds = [];
    	foreach ($bets as $key => $item) {
    		$matche = $this->betService->getOddsMatcheInCache($item['id_league'], $item['id_matche']);

            switch ($item['type_bet']) {
                case 'full_time_result':
                    array_push($listOdds, $this->fullTimeResult($matche['odds']->main->sp->full_time_result->odds, $item['bet']));
                    break;
                case 'double_chance':
                    array_push($listOdds, $this->doubleChance($matche['odds']->main->sp->full_time_result->odds, $item['bet']));
                    break;
                
                default:
                    array_push($listOdds, 1);
                    break;
            }
    	}

    	$result = array_product($listOdds)*$valuePurchase;
    	return $result;
    }

    public function fullTimeResult($odds, $itemBet){
        if($itemBet == 'home') return (double) $odds[0]->odds;
        if($itemBet == 'draw') return (double) $odds[1]->odds;
        if($itemBet == 'away') return (double) $odds[2]->odds;
    }

    public function doubleChance($odds, $itemBet){
        if($itemBet == 'home-or-draw') return (double) $odds[0]->odds;
        if($itemBet == 'draw-or-away') return (double) $odds[1]->odds;
        if($itemBet == 'home-or-away') return (double) $odds[2]->odds;
    }
}