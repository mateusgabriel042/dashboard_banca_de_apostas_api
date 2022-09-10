<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Services\PayloadService;

class PayloadController extends Controller {
	use ApiResponser;

	private $payloadService;

	public function __construct(PayloadService $payloadService){
		$this->payloadService = $payloadService;
	}

    public function createPayment($value){
    	try {
            $linkPayment = $this->payloadService->createPayment($value);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    	
        return ['linkPayment' => $linkPayment];
    	return $this->success([
            'linkPayment' => $linkPayment,
        ],  'Link de pagamento gerado com sucesso!');
    }

    public function verifyPayment(Request $request){
    	try {
            $dataPayment = $this->payloadService->verifyPayment($request);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    	
    	return $this->success([
            'dataPayment' => $dataPayment,
        ],  'Dados do pagamento listado com sucesso!');
    }
}
