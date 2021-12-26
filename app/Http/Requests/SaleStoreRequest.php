<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class SaleStoreRequest extends FormRequest
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
        $rules = [
            'party_id' => 'required|not_in:0',
            'status_id' => 'required',
            'product_ids.*' => 'required|not_in:0',
            'sheet_production_ids.*' => 'required',
            'price.*' => 'required',
            'qty.*' => 'required|lte:remaining_qty.*',
            'pack.*' => 'required|lte:remaining_pack.*',
            'weight.*' => 'required|lte:remaining_weight.*'
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'product_ids.*' => 'product',
            'price.*' => 'price',
            'qty.*' => 'quantity',
            'pack.*' => 'pack',
            'weight.*' => 'weight'
        ];
    }


    public function messages(){
        return [
            'sheet_production_ids.*.required' => 'The sheet field is required. ',
            'quantity.*.required' => 'The quantity field is required.'
        ];
    }
}
