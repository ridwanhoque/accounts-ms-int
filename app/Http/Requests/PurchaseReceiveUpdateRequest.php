<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class PurchaseReceiveUpdateRequest extends FormRequest
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
            'purchase_id' => 'required|not_in:0',
            'chalan_number' => 'required|unique:purchase_receives,'.$this->id,',id',
            'quantity.*' => 'required'
        ];
    }


    public function messages(){
        return [
            'quantity.*.required' => 'The quantity field is required.'
        ];
    }
}
