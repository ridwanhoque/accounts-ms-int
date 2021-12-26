<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class FundTransferUpdateRequest extends FormRequest
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
            'from_account_id' => 'required|different:to_account_id|not_in:0',
            'to_account_id' => 'required|different:from_account_id|not_in:0',
            'amount' => 'required|lte:account_balance|min:1',
            'description' => 'required',
            'fund_transfer_image' => 'required|image|max:1024'
        ];
    }
}
