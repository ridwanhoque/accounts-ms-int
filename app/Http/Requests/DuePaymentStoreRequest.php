<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class DuePaymentStoreRequest extends FormRequest
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
            'account_information_id' => 'required|not_in:0',
            'payment_method_id' => 'required|not_in:0',
            'paid_amount' => 'required|numeric|min:1|lte:due_amount'
        ];
    }
}
