<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChartOfAccountStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'head_name' => 'required|max:100',
            'chart_of_account_type_id' => 'required',
            'parent_id' => 'required',
            'head_name' => 'required|unique'
        ];
    }
}
