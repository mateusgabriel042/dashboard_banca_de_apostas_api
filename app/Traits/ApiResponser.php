<?php

namespace App\Traits;

trait ApiResponser {
	protected function success($data, $message = null, $code = 200) {
		return response()->json([
			'status' => 'Success',
			'message' => $message,
			'data' => $data
		], $code);
	}

	protected function error($message = null, $code, $errors = null) {
		$codes = ['401', '403', '404', '422'];
		if(!in_array($code, $codes)){
			$code = '500';
		}
		return response()->json([
			'status' => 'Error',
			'message' => $message,
			'code' => $code,
			'errors' => $errors,
		], $code);
	}

	protected function verifyValidation($request){
        return $this->error('Erro na requisição!', 422, $request->validator->messages());
	}

	protected function verifyValidationRecursive($request1, $request2){
        $errors = array_merge_recursive($request1->validator->messages()->toArray(), $request2->validator->messages()->toArray());
        return $this->error('Erro na requisição!', 422, $errors);
	}

}