<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Auth;

class SupplierUpdateRequest extends FormRequest
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
            'name' => 'required|max:160',
            'email' => 'required|unique:suppliers,email,'.$request->id.'|max:160',
            'phone' => 'required|unique:suppliers,phone,'.$request->id.'|regex: /(01)[0-9]{9}/|size:11'
        ];
    }
}
