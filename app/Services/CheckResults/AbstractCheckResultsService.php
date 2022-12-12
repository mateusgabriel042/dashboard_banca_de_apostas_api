<?php

namespace App\Services\CheckResults;

use Illuminate\Http\Request;
use App\Services\CheckResults\CheckResultsSoccer;
use App\Models\Bet;

class AbstractCheckResultsService {
	private $bet;

    public function __construct(Bet $bet){
        $this->bet = $bet;
    }

    public function checkResult(){
    	switch ($this->bet->apievents_sport_id) {
    		case 1:
    			$checkResult = new CheckResultsSoccer($this->bet->bet365_matche_id);
    			return $checkResult->checkResult($this->bet->subtype_bet, $this->bet->customer_bet);
    			break;
    		
    		default:
    			return 0;
    			break;
    	}
    }
}