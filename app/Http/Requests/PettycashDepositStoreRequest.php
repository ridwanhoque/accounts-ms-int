<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class PettycashDepositStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'received_by' => 'required',
            'account_information_id' => 'required|not_in:0',
            'amount' => 'required|lte:account_balance'
        ];
    }

    public function attributes(){
        return [
            'account_information_id' => 'account holder',
            'account_balance' => 'available balance'
        ];
    }
}
