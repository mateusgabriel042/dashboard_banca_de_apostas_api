<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\League;

class LeagueService extends AbstractService {
    private $role;
    private $model;

    public function __construct($role, $model){
        parent::__construct($role, $model);
        $this->role = $role;
        $this->model = $model;
    }

    public function getByCountry($countryId){
        $leagues = League::where('country_id', '=', $countryId)->get();
        if($leagues != null)
            return $leagues;
        else
            return [];
    }

    public function allCountryLeagues(){
        return Country::with(['leagues'])->where('is_active', '=', 1)->get();
    }

    public function allLeagueMatches($apieventsSportId, $apieventsLeagueId){
        $league = $this->model->with(['country', 'matches'])->where('is_active', '=', 1)
                                                                 ->where('apievents_sport_id', '=', $apieventsSportId)
                                                                 ->where('apievents_league_id', '=', $apieventsLeagueId)
                                                                 ->first();
        
        foreach ($league->matches as $keyMatche => $matche) {
            if($matche['object_odds_prematche'] != null){
                $league->matches[$keyMatche]['object_odds_prematche'] = json_decode($matche['object_odds_prematche']);
            }   
        }

        return $league;
    }

    public function updateActiveLeagues($request){
    	$leaguesSelected = $request['leagues'];
    	foreach ($leaguesSelected as $key => $item) {
    		$league = $this->model->find($item['id']);
    		$league->is_active = $item['value'];
    		$league->save();
    	}
    	return $leaguesSelected;
    }
}