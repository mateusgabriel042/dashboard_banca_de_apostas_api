<?php

namespace App\Services;

use Illuminate\Http\Request;

class LeagueService extends AbstractService {
    private $role;
    private $model;

    public function __construct($role, $model){
        parent::__construct($role, $model);
        $this->role = $role;
        $this->model = $model;
    }

    public function getByCountry($countryId){
    	return $this->model->where('country_id', '=', $countryId)->get();
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