<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LedgerStoreRequest extends FormRequest
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
            'payment_date' => 'required',
            'chart_of_account_id.*' => 'required|distinct',
            'debit_amount.*' => 'required_without:credit_amount.*|gt:0|nullable',
            'credit_amount.*' => 'required_without:debit_amount.*|gt:0|nullable',
            'balance' => 'numeric|between:0,0'
        ];
    }
}