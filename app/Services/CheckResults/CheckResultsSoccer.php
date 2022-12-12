<?php

namespace App\Services\CheckResults;

use Illuminate\Http\Request;
use App\Models\Matche;
use App\Models\ResultSoccer;

class CheckResultsSoccer {
	private $bet365MatcheId;
	private $matche;
	private $resultSoccer;

    public function __construct($bet365MatcheId){
        $this->bet365MatcheId = $bet365MatcheId;
        $this->matche = Matche::where('bet365_matche_id', '=', $bet365MatcheId)
        					  ->where('apievents_sport_id', '=', 1)
        					  ->first();
        $this->resultSoccer = ResultSoccer::where('matche_id', '=', $this->matche->id)->first();
    }

    public function checkResult($subtypeBet, $customerBet){
    	switch ($subtypeBet) {
    		case 'full_time_result':
    			return $this->checkFullTimeResult($customerBet);
    			break;
    		default:
    			return 0;
    			break;
    	}
    }

    public function checkFullTimeResult($customerBet){
    	if($this->resultSoccer->home_goals > $this->resultSoccer->away_goals && $customerBet == '1'){return true;}
        if($this->resultSoccer->home_goals == $this->resultSoccer->away_goals && $customerBet == 'draw'){return true;}
        if($this->resultSoccer->home_goals < $this->resultSoccer->away_goals && $customerBet == '2'){return true;}
        return false;
    }
}