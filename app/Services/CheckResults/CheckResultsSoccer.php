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
            case 'double_chance':
                return $this->doubleChanceResult($customerBet);
                break;
            case 'goals_over_under':
                return $this->goalsOverUnderResult($customerBet);
                break;
            case 'both_teams_to_score':
                return $this->bothTeamsToScoreResult($customerBet);
                break;
            case 'half_time_result':
                return $this->halfTimeResult($customerBet);
                break;
            case 'corners_2_way':
                return $this->cornersTwoWay($customerBet);
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

    public function doubleChanceResult($customerBet){
        if($this->resultSoccer->home_goals > $this->resultSoccer->away_goals){
            if($customerBet == 'home_or_draw' || $customerBet == 'home_or_away')
                return true;
        }

        if($this->resultSoccer->home_goals == $this->resultSoccer->away_goals){
            if($customerBet == 'home_or_draw' || $customerBet == 'draw_or_away')
                return true;
        }

        if($this->resultSoccer->home_goals < $this->resultSoccer->away_goals){
            if($customerBet == 'draw_or_away' || $customerBet == 'home_or_away')
                return true;
        }
        
        return false;
    }

    public function goalsOverUnderResult($customerBet){
        $type = explode("_", $customerBet)[0];
        $numberGoals = floatval(explode("_", $customerBet)[1]);
        $countGoals = $this->resultSoccer->home_goals + $this->resultSoccer->away_goals;

        if($type == 'over' && $countGoals > $numberGoals)
            return true;
        if($type == 'under' && $countGoals < $numberGoals)
            return true;

        return false;
    }

    public function bothTeamsToScoreResult($customerBet){
        if($customerBet == 'yes')
            if($this->resultSoccer->home_goals > 0 && $this->resultSoccer->away_goals > 0)
                return true;
        }

        if($customerBet == 'no')
            if($this->resultSoccer->home_goals == 0 || $this->resultSoccer->away_goals == 0){
                return true;
        }
        
        return false;
    }

    public function halfTimeResult($customerBet){
        if($this->resultSoccer->home_goals_first_time > $this->resultSoccer->away_goals_first_time && $customerBet == '1'){return true;}
        if($this->resultSoccer->home_goals_first_time == $this->resultSoccer->away_goals_first_time && $customerBet == 'draw'){return true;}
        if($this->resultSoccer->home_goals_first_time < $this->resultSoccer->away_goals_first_time && $customerBet == '2'){return true;}
        return false;
    }

    public function cornersTwoWay($customerBet){
        $type = explode("_", $customerBet)[0];
        $numberCorners = floatval(explode("_", $customerBet)[1]);
        $countCorners = $this->resultSoccer->home_corners + $this->resultSoccer->away_corners;

        if($type == 'over' && $countCorners > $numberCorners)
            return true;
        if($type == 'under' && $countCorners < $numberCorners)
            return true;

        return false;
    }
}