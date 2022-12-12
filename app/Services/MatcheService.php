<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Country;

class MatcheService extends AbstractService {
    private $role;
    private $model;

    public function __construct($role, $model){
        parent::__construct($role, $model);
        $this->role = $role;
        $this->model = $model;
    }

    public function getMatche($apieventsSportId, $apieventsLeagueId, $bet365MatcheId){
    	$matche = $this->model->with('league')
    						  ->where('apievents_sport_id', '=', $apieventsSportId)
                              ->where('apievents_league_id', '=', $apieventsLeagueId)
                              ->where('bet365_matche_id', '=', $bet365MatcheId)
                              ->first();

        if($matche['object_odds_prematche'] != null){
            $matche['object_odds_prematche'] = json_decode($matche['object_odds_prematche']);
        }

        return $matche;
    }
}