<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class DailyProductionStoreRequest extends FormRequest
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
            'product_ids.*' => 'required|not_in:0',
            'todays_weight.*' => 'required|lte:sheet_stock_kg.*',
            'used_rolls.*' => 'required|lte:sheet_stock_roll.*',
            'finish_quantity.*' => 'required',
            'pack.*' => 'required',
            'net_weight.*' => 'required',
            'wastage_out.*' => 'required',
            'sheet_size_color_ids.*' => 'required|not_in:0|distinct'
        ];

        return $rules;
    }

    public function attributes(){
        return [
            'product_ids.*' => 'product',
            'todays_weight.*' => 'used kg',
            'finish_quantity.*' => 'finish product',
            'pack.*' => 'pack',
            'net_weight.*' => 'net weight',
            'wastage_out.*' => 'kutch wastage'
        ];
    }
}
