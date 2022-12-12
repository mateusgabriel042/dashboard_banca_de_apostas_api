<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Bet;
use App\Models\User;
use App\Models\Matche;
use Illuminate\Support\Arr;
use Auth;
use App\Services\ValuePrematchePurchaseService;


class BetPurchaseService extends AbstractService {
    private $role;
    private $model;
    private $valuePrematchePurchaseService;

    public function __construct($role, $model){
        parent::__construct($role, $model);
        $this->role = $role;
        $this->model = $model;
        $this->valuePrematchePurchaseService = new ValuePrematchePurchaseService();
    }

    public function create($request){

        //pegando todos os dados da requisicao
        $dataRequest = $request->all();

        //buscando o usuario
        $user = User::find(Auth::user()->id);

        //verificando se o usuario tem saldo suficiente para realizar a aposta
        if($user->money - (double) $dataRequest['invested_money'] >= 0){
            $user['money'] = $user['money'] - (double) $dataRequest['invested_money'];
            $user->save();
            $idsMatches = Arr::pluck($dataRequest['bets'], 'bet365_matche_id');
            $matchesOdds = Matche::whereIn('bet365_matche_id', $idsMatches)->get();
            $totalValueOdds = 1;
            $listOdds = [];
            foreach ($dataRequest['bets'] as $key => $item) {
                $matche = $matchesOdds->where('bet365_matche_id', '=', $item['bet365_matche_id'])->first();
                $odds = json_decode($matche['object_odds_prematche'], true);
                $typeBet = $item['type_bet'];
                $subtypeBet = $item['subtype_bet'];
                $oddId = $item['odd_id'];
                $odd = (float) $odds[$typeBet]['sp'][$subtypeBet]['odds'][$oddId]['odds'];
                $totalValueOdds *= $odd;
                $listOdds += [$item['bet365_matche_id'] => $odd];
            }

            $betPurchaseRegistered = $this->model->create([
                'user_id' => $user->id,
                'invested_money' => $dataRequest['invested_money'],
                'date_purchase' => date('Y-m-d H:i:s'),
                'return_money' => $totalValueOdds * $dataRequest['invested_money'],
                'win' => 0,
                'is_active' => 1,
            ]);

            foreach ($dataRequest['bets'] as $key => $item) {
                $dataBet = Bet::create([
                    'type_event' => $item['type_event'],
                    'type_bet' => $item['type_bet'],
                    'subtype_bet' => $item['subtype_bet'],
                    'customer_bet' => $item['customer_bet'],
                    'apievents_sport_id' => $item['apievents_sport_id'],
                    'apievents_league_id' => $item['apievents_league_id'],
                    'bet365_matche_id' => $item['bet365_matche_id'],
                    'odd_id' => $item['odd_id'],
                    'odd' => $listOdds[$item['bet365_matche_id']],
                    'bet_purchase_id' => $betPurchaseRegistered['id'],
                    'win' => 0,
                    'is_active' => 1,
                ]);
            }

        }else{
            return [];
        }
    }

    public function getReturnMoneyBet ($bets){
        
    }

}