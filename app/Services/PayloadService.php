<?php

namespace App\Services;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Deposit;
use App\Models\User;
use Auth;

class PayloadService {
	private $baseURL = 'https://api.mercadopago.com/';
	private $URL_PAYMENT;
	private $access_token = 'APP_USR-3107300560126090-082116-22a7f4a38fcf872d71ddfb8bb8ee7135-297817271';
	private $client;
    private $URL_WEBHOOK_TESTE = 'https://webhook.site/1dc70c43-9967-42b9-bcfb-c8845fdeffcc';
    private $URL_WEBHOOK_PRODUCTION = 'https://apibancadeaposta.betagencia.com/api/payload/verify-payment';

	public function __construct(){
        $this->client = new Client();
        $this->URL_PAYMENT = $this->baseURL.'v1/payments';
    }

    public function createPayment($value){
    	\MercadoPago\SDK::setAccessToken($this->access_token);
    	$preference = new \MercadoPago\Preference();
    	$item = new \MercadoPago\Item();
    	$item->title = 'Deposito de aposta';
    	$item->quantity = 1;
    	$item->unit_price = (double) $value;
    	$preference->items = array($item);
    	$preference->back_urls = array(
    		"success" => 'https://google.com/success',
    		"failure" => 'https://google.com/failure',
    		"pending" => 'https://google.com/pending',
    	);

    	$preference->notification_url = $this->URL_WEBHOOK_TESTE;
    	$preference->external_reference = Auth::user()->id;
    	$preference->save();
    	$link = $preference->init_point;

    	return $link;
    }

    public function verifyPayment($request){
    	$dataRequest = $request->all();

    	if($request->has('action')){
    		$colletion_id = $dataRequest['data']['id'];
    	}else{
    		return null;
        }

        $headers = [
            'Authorization' => 'Bearer '.$this->access_token,
        ];

        $response = $this->client->request('GET', $this->URL_PAYMENT.'/'.$colletion_id, ['headers' => $headers]);

        $responseBody = json_decode($response->getBody());

        //return $responseBody;
        

        $deposit = Deposit::where('colletion_id', '=', $colletion_id)->first();
        if($deposit == null){
	        $deposit = Deposit::create([
	        	'transaction_amount' => $responseBody->transaction_amount,
				'type_payment' => $responseBody->payment_method_id,
				'colletion_id' => $colletion_id,
				'payer_email' => $responseBody->payer->email,
				'currency_id' => $responseBody->currency_id,
				'identification_type' => $responseBody->payer->identification->type,
				'identification_number' => $responseBody->payer->identification->number,
				'external_reference' => $responseBody->external_reference,
				'qr_code' => $responseBody->point_of_interaction->transaction_data->qr_code,
				'qr_code_base64' => $responseBody->point_of_interaction->transaction_data->qr_code_base64,
				'status' => $responseBody->status,
				'transaction_id' => $responseBody->transaction_details->transaction_id,
				'bank_transfer_id' => $responseBody->transaction_details->bank_transfer_id,
				'user_id' => $responseBody->external_reference,
	        ]);
        }else{
        	$deposit->update([
	        	'transaction_amount' => $responseBody->transaction_amount,
				'type_payment' => $responseBody->payment_method_id,
				'colletion_id' => $colletion_id,
				'payer_email' => $responseBody->payer->email,
				'currency_id' => $responseBody->currency_id,
				'identification_type' => $responseBody->payer->identification->type,
				'identification_number' => $responseBody->payer->identification->number,
				'external_reference' => $responseBody->external_reference,
				'qr_code' => $responseBody->point_of_interaction->transaction_data->qr_code,
				'qr_code_base64' => $responseBody->point_of_interaction->transaction_data->qr_code_base64,
				'status' => $responseBody->status,
				'transaction_id' => $responseBody->transaction_details->transaction_id,
				'bank_transfer_id' => $responseBody->transaction_details->bank_transfer_id,
				'user_id' => $responseBody->external_reference,
	        ]);
        }

        if($deposit->status == 'approved'){
            $user = User::find($deposit->user_id);
            $user['money'] += $deposit->transaction_amount;
            $user->save();
        }
        

    	return $responseBody;
    }
}