<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class ProductStoreRequest extends FormRequest
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
            'raw_material_id' => 'required',
            'color_id' => 'required',
            'machine_id' => 'required',
            'name' => 'required|unique_with:products,raw_material_id,color_id|max:160',
        ];
    }
}
