<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sport;

class SportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$listSports = [
    		['apievents_id' => 1, 'name' => 'Soccer', 'label' => 'Futebol'],
    		['apievents_id' => 2, 'name' => 'Horse Racing', 'label' => 'Corrida de cavalo'],
    		['apievents_id' => 3, 'name' => 'Cricket', 'label' => 'Grilo'],
    		['apievents_id' => 4, 'name' => 'Greyhounds', 'label' => 'Galgos'],
    		['apievents_id' => 8, 'name' => 'Rugby Union', 'label' => 'União do rugby'],
    		['apievents_id' => 9, 'name' => 'Boxing/UFC', 'label' => 'Boxe/UFC'],
    		['apievents_id' => 12, 'name' => 'American Football', 'label' => '	Futebol americano'],
    		['apievents_id' => 13, 'name' => 'Tennis', 'label' => 'Tênis'],
    		['apievents_id' => 14, 'name' => 'Snooker', 'label' => 'Sinuca'],
    		['apievents_id' => 15, 'name' => 'Darts', 'label' => 'Dardos'],
    		['apievents_id' => 16, 'name' => 'Baseball', 'label' => 'Beisebol'],
    		['apievents_id' => 17, 'name' => 'Ice Hockey', 'label' => 'Hockey no gelo'],
    		['apievents_id' => 18, 'name' => 'Basketball', 'label' => 'Basquetebol'],
    		['apievents_id' => 19, 'name' => 'Rugby League', 'label' => 'Liga de Rugby'],
    		['apievents_id' => 36, 'name' => 'Australian Rules', 'label' => 'Regras australianas'],
    		['apievents_id' => 66, 'name' => 'Bowls', 'label' => 'Tigelas'],
    		['apievents_id' => 75, 'name' => 'Gaelic Sports', 'label' => 'Esportes gaélicos'],
    		['apievents_id' => 78, 'name' => 'Handball', 'label' => 'Handebol'],
    		['apievents_id' => 83, 'name' => 'Futsal', 'label' => 'Futsal'],
    		['apievents_id' => 90, 'name' => 'Floorball', 'label' => 'Floorball'],
    		['apievents_id' => 91, 'name' => 'Volleyball', 'label' => 'Vôlei'],
    		['apievents_id' => 92, 'name' => 'Table Tennis', 'label' => 'Tênis de mesa'],
    		['apievents_id' => 94, 'name' => 'Badminton', 'label' => 'Badminton'],
    		['apievents_id' => 95, 'name' => 'Beach Volleyball', 'label' => 'Vôlei de praia'],
    		['apievents_id' => 107, 'name' => 'Squash', 'label' => 'Abóbora'],
    		['apievents_id' => 110, 'name' => 'Water Polo', 'label' => 'Pólo aquático'],
    		['apievents_id' => 151, 'name' => 'E-sports', 'label' => 'E-sports'],
    	];
    	foreach ($listSports as $key => $item) {
    		Sport::create($item);
    	}
    }
}


	


	
	
	




	


	






	




