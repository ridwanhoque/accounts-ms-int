<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductStockTransferStoreRequest extends FormRequest
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
            'product_ids.*' => 'required|not_in:0|distinct',
            'to_branch' => 'required',
            'quantity.*' => 'required|numeric|lte:available_quantity.*',
            'pack.*' => 'required|numeric|lte:available_pack.*',
            'weight.*' => 'required|numeric|lte:available_weight.*'
        ];
    }
}
