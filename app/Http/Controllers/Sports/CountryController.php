<?php

namespace App\Http\Controllers\Sports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Sports\CountryService as SportsCountryService;
use App\Traits\ApiResponser;

class CountryController extends Controller
{
    use ApiResponser;

	private $sportsCountryService;
	
	public function __construct(SportsCountryService $sportsCountryService){
		$this->sportsCountryService = $sportsCountryService;
	}

	public function registerCountryInDB(){
		try {
            $countriesRegistered = $this->sportsCountryService->registerCountriesDB();
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

        return $this->success([
            'countriesRegistered' => $countriesRegistered,
        ],  'Paises registrados com sucesso!');
	}
}
