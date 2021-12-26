<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class PettycashExpenseUpdateRequest extends FormRequest
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
            'pettycash_chart_ids.*' => 'required|not_in:0',
            'purpose.*' => 'required',
            'amount.*' => 'required'
        ];
    }

    public function messages(){
        return [
            'pettycash_chart_ids.*.required' => 'The account chart field is required.',
            'purpose.*.required' => 'The purpose field is required',
            'amount.*.required' => 'The amount field is required.'
        ];
    }
}
