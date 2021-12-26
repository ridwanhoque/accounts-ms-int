<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\RawMaterialStockCheck;
use Auth;

class SheetProductionStoreRequest extends FormRequest
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
            'sheet_production_date' => 'required',
            'sheet_production_reference' => 'unique:sheet_productions',
            'status_id' => 'required',
            'sub_raw_material_ids.*' => 'required|not_in:0',
            'qty_kgs.*' => 'required|lte:raw_material_stock_qty.*',
            'total_kg' => 'nullable|numeric|min:1',
            'total_roll' => 'nullable|numeric|min:1',
            'haddi' => 'nullable|numeric',
            'powder' => 'nullable|numeric',
            'sheet_size_ids.*' => 'required|not_in:0|distinct',
            'qty_rolls.*' => 'required|numeric|min:1',
            'qty_kgs_details.*' => 'required|numeric|min:1',
            'fm_kutcha_in_kgs.*' => 'nullable|numeric|min:1|lte:fm_kutcha_stock.*',
            'total_input' => 'numeric|same:sheet_with_wastage',
            'fm_kutcha_in_ids.*' => 'nullable|distinct',
            'fm_kutcha_ids.*' => 'nullable|distinct'
        ];
    }


    public function attributes(){
        return [
            'sub_raw_material_ids.*' => 'sub raw material',
            'qty_kgs.*' => 'qty (kg)',
            'sheet_size_ids.*' => 'sheet size',
            'qty_rolls.*' => 'sheet roll(qty)',
            'qty_kgs_details.*' => 'sheet kg(qty)',
            'fm_kutcha_in_kgs.*' => 'fm kutcha qty'
        ];
    }
}
