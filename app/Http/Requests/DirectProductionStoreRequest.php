<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class DirectProductionStoreRequest extends FormRequest
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
            'qty_kgs.*' => 'required|lte:raw_material_stock.*',
            'sub_raw_material_ids.*' => 'nullable',
            'todays_weight.*' => 'required',
            'finish_quantity.*' => 'required',
            'pack.*' => 'required',
            // 'net_weight.*' => 'required',
            // 'fm_kutcha_ids.*' => 'required_with:kutcha_qty.*|not_in:0',
            // 'kutcha_qty.*' => 'required',
            'fm_kutcha_in_kgs.*' => 'nullable|lte:fm_kutcha_stock.*',
            'product_ids.*' => 'required|not_in:0'
        ];
    }

    public function attributes(){
        return [
            'sub_raw_material_ids.*' => 'raw material',
            'todays_weight.*' => 'todays weight',
            'finish_quantity.*' => 'finish quantity',
            'pack.*' => 'pack',
            // 'net_weight.*' => 'net weight',
            'fm_kutcha_ids.*' => 'wastage',
            'kutcha_qty.*' => 'kutcha quantity',
            'fm_kutcha_in_kgs.*' => 'assigned kutcha qty (kg)'   
        ];
    }
}
