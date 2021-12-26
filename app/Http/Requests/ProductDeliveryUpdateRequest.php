<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Auth;

class ProductDeliveryUpdateRequest extends FormRequest
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
    public function rules(Request $request)
    {  
        return [
            'product_delivery_chalan' => 'required|unique:product_deliveries,id,'.$this->product_delivery_chalan,
            'driver_name' => 'nullable',
            'driver_phone' => 'nullable|regex: /(01)[0-9]{9}/ |size:11',
            'status_id' => 'required',
            'product_ids.*' => 'required|not_in:0',
            'sale_ids.*' => 'required',
            'quantity.*' => 'required|lte:remaining_qty.*',
            'pack.*' => 'required|lte:remaining_pack.*',
            'weight.*' => 'required|lte:remaining_weight.*'
        ];
    }

    public function messages(){
        return [
            'product_ids.*.required' => 'The product field is required.',
            'sale_ids.*.required' => 'The sales id field is required',
            'quantity.*.required' => 'The quantity field is required.',
        ];
    }
}
