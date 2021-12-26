<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
use Illuminate\Http\Request;

class SheetProductionUpdateRequest extends FormRequest
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
            'qty_kgs.*' => 'required|lte:raw_material_stock_qty.*',
            'sheet_production_date' => 'required',
            'sheet_production_reference' => 'unique:sheet_productions',
            'status_id' => 'required',
            'total_kg' => 'nullable|numeric|min:1',
            'total_roll' => 'nullable|numeric|min:1',
            'sheet_size_id.*' => 'required|not_in:0',
            'color_id.*' => 'required|not_in:0',
            'outer-group.*.qty_rolls' => 'required|numeric|min:1',
            'outer-group.*.qty_kgs_details' => 'required|min:1',
            'outer-group.*.inner-group.*.raw_material_ids' => 'required',
            'outer-group.*.inner-group.*.qty_kgs' => 'required'
        ];
    }

    public function messages(){
        $messages = [
            'outer-group.*.qty_rolls.required' => 'The qty(roll) field is required.',
            'outer-group.*.qty_rolls.min' => 'The qty(roll) must be at least 1.',
            'outer-group.*.qty_kgs_details.required' => 'The qty(kg) field is required.',
            'outer-group.*.qty_kgs_details.min' => 'The qty(kg) must be at least 1.',
            'outer-group.*.inner-group.*.raw_material_ids.required' => 'The raw material field is required.',
            'outer-group.*.inner-group.*.qty_kgs.required' => 'The raw material qty field is required.'
        ];

        return $messages;
    }
}
