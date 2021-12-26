<?php

namespace App\Http\Requests\Accounts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ReceiveVoucherStoreRequest extends FormRequest
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
            'credit_chart_ids.*' => 'required|numeric|min:1|distinct',
            'credit_description.*' => 'nullable',
            'credit_amount.*' => 'required|lte:credit_balance.*',
            'debit_chart_ids.*' => 'required|numeric|min:1|distinct',
            'debit_description.*' => 'nullable',
            'debit_amount.*' => 'required',
            'total_debit_amount' => 'numeric|same:total_credit_amount'
        ];
    }
}
