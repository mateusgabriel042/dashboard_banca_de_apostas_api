<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Bet;
use App\Models\User;
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
    	$dataRequest = $request->all();

    	$user = User::find(Auth::user()->id);
    	if($user->money - (double) $dataRequest['value_bet'] >= 0){
            $dataBetPurchase = [
        		'user_id' => Auth::user()->id,
        		'value_bet' => $dataRequest['value_bet'],
        		'date_purchase' => date('Y-m-d H:i:s'),
        		'return_bet' => $this->valuePrematchePurchaseService->fullTimeResult($dataRequest['bets'], $dataRequest['value_bet'])
        	];

            $betPurchaseRegistered = $this->model->create($dataBetPurchase);

            foreach ($dataRequest['bets'] as $key => $item) {
            	$dataBet = Bet::create([
            		'bet' => $item['bet'],
            		'bet_purchase_id' => $betPurchaseRegistered['id'],
            		'result_final' => null,
            		'win' => 0,
            		'type_bet' => $item['type_bet'],
            		'bet_id' => $item['bet_id'],
            		'id_matche' => $item['id_matche'],
            	]);
            }

            $user['money'] -= (double) $dataRequest['value_bet'];
            $user->save();

            return $betPurchaseRegistered;
        }
        
        return [];
    }

}