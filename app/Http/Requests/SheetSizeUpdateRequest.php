<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Auth;

class SheetSizeUpdateRequest extends FormRequest
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
        // dd($this->raw_material_id);
        return [
            'name' => 'required|unique:sheet_sizes,name,'.$this->id.',id,raw_material_id,'.$this->raw_material_id,
            'raw_material_id' => 'required'
        ];
    }
}
