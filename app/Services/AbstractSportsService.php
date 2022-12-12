<?php

namespace App\Services;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class AbstractSportsService {

    public function __construct(){
        $this->client = new Client();
    }

    public function qtdDaysSearch(){
        return 4;
    }

    public function requireAPIOut($typeReq, $sportName, $routeName, $params){
        $response = $this->client->request($typeReq, $this->getRouteURL($sportName, $routeName), [
            'query' => $params,
            'headers' => $this->getHeaders(),
        ]);

        $responseBody = json_decode($response->getBody());

        return $responseBody->response;
    }


    public function getToken(){
        return '4967d1e0d5ef67d1ccf45d66b49cf9b6';
    }

    public function getRouteURL($sportName, $routeName){
        return $this->getURLSport($sportName).'/'.$this->getRoute($routeName);
    }

    public function getURLSport($urlName){
        $URLs = [
            'FOOTBALL' => 'https://v3.football.api-sports.io',
            'BASEBALL' => 'https://v1.baseball.api-sports.io',
            'BASKETBALL' => 'https://v1.basketball.api-sports.io',
            'HANDBALL' => 'https://v1.handball.api-sports.io',
            'HOCKEY' => 'https://v1.hockey.api-sports.io',
            'RUGBY' => 'https://v1.rugby.api-sports.io',
            'VOLLEYBALL' => 'https://v1.volleyball.api-sports.io',
        ];
        return $URLs[$urlName];
    }

    public function getRoute($routeName){
        $URLs = [
            'ROUTE_COUNTRIES' => 'countries',
            'ROUTE_LEAGUES' => 'leagues',
            'ROUTE_COMPETITIONS' => 'competitions',
            'ROUTE_MATCHES' => 'fixtures',
            'ROUTE_GAMES' => 'games',
            'ROUTE_ODDS_PREMATCHE' => 'odds',
            'ROUTE_ODDS_LIVE' => 'odds/live',
        ];
        return $URLs[$routeName];
    }

    public function getSportsArray(){
        return [
            [
                'name' => 'FOOTBALL',
                'label' => 'Futebol',
            ],
            [
                'name' => 'BASEBALL',
                'label' => 'Basebol',
            ],
            [
                'name' => 'BASKETBALL',
                'label' => 'Basquetebol',
            ],
            [
                'name' => 'HANDBALL',
                'label' => 'Handebol',
            ],
            [
                'name' => 'HOCKEY',
                'label' => 'Hóquei',
            ],
            [
                'name' => 'RUGBY',
                'label' => 'Rúgbi',
            ],
            [
                'name' => 'VOLLEYBALL',
                'label' => 'Voleibol',
            ],
        ];
    }

    public function getHeaders(){
        return [
            'x-rapidapi-key' => $this->getToken(),
        ];
    }
}