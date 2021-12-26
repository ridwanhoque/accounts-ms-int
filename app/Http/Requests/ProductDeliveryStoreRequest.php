<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CheckExceeds;
use Auth;

class ProductDeliveryStoreRequest extends FormRequest
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
            'product_delivery_chalan' => 'required|unique:product_deliveries',
            'driver_name' => 'nullable',
            'driver_phone' => 'nullable|regex: /(01)[0-9]{9}/ |size:11',
            'status_id' => 'required',
            'product_ids.*' => 'required|not_in:0',
            'sale_ids.*' => 'required',
            'quantity.*' => 'required|lte:remaining_qty.*',
            'pack.*' => 'required|lte:product_stock_pack.*',
            'weight.*' => 'required|lte:product_stock_weight.*'
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
