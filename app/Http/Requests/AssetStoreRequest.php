<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AssetStoreRequest extends FormRequest
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
            'asset_chart_id.*' => 'required|not_in:0',
            'amount.*' => 'required',
            'years.*' => 'required',
            'account_information_id.*' => 'required|not_in:0',
            'payment_method_id.*' => 'required|not_in:0'
        ];
    }

    public function attributes()
    {
        return [
            'asset_chart_id.*' => 'asset head',
            'amount.*' => 'amount',
            'years.*' => 'years',
            'account_information_id.*' => 'account information',
            'payment_method_id.*' => 'payment method'
        ];
    }
}
