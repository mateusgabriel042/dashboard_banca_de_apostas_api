<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Contracts\Validation\Validator;

class BetUpdateRequest extends FormRequest
{
    public $validator = null;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'bet_purchase_id' => 'required',
            'sport_name' => 'required|string',
            'country_code' => 'required|string',
            'league_id' => 'required',
            'matche_id' => 'required',
            'bet_id' => 'required',
            'odd_id' => 'required',
            'type_event' => 'required',
        ];
    }

    protected function failedValidation(Validator $validator) {
        $this->validator = $validator;
    }
}
