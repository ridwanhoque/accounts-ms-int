<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class DailyProductionUpdateRequest extends FormRequest
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
            'standard_weight.*' => 'required',
            'todays_weight.*' => 'required',
            'finish_quantity.*' => 'required',
            'pack.*' => 'required',
            'net_weight.*' => 'required'
        ];

        return $rules;
    }

    public function messages(){
        $messages = [
            'product_ids.*.required' => 'The product field is required.',
            'product_ids.*.not_in' => 'The product field value is not valid.',
            'standard_weight.*.required' => 'The standard weight field is required.',
            'todays_weight.*.required' => 'The today weight field is required.',
            'finish_quantity.*.required' => 'The finish quantity field is required.',
            'pack.*.required' => 'The pack field is required.',
            'net_weight.*' => 'The net weight field is required.' 
        ];

        return $messages;
    }
}
