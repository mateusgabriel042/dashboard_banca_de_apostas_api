<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use GuzzleHttp\Client;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    public function run()
    {
    	$client = new Client();
    	set_time_limit(4000);

        $routeCountries = 'https://betsapi.com/docs/samples/countries.json';

        $response = $client->request('get', $routeCountries, []);

        $responseBody = json_decode($response->getBody());

        foreach ($responseBody->results as $key => $item) {
			Country::create([
				'name' => $item->name,
		        'label' => $item->name,
		        'code' => $item->cc,
		        'flag' => null,
			]);
		}
    }
}
