<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
use App\FundTransfer;
use App\Rules\FundTransferCheck;
use Uploader;

class FundTransferStoreRequest extends FormRequest
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

    public function attributes(){
        return [
            'from_account_id' => 'from account',
            'to_account_id' => 'to account',
            'account_balance' => 'available account balance'
        ];
    }

    public function store()
    {
        $fund_transfer = new FundTransfer;
        $fund_transfer->from_account_id = $this->from_account_id;
        $fund_transfer->to_account_id = $this->to_account_id;
        $fund_transfer->fund_transfer_date = $this->fund_transfer_date;
        $fund_transfer->amount = $this->amount;
        $fund_transfer->description = $this->description;
        //image path save

        $fund_transfer_image = '';
        if($this->has('fund_transfer_image')){
            $fund_transfer_image = Uploader::upload_image('images/fund_transfers', $this->file('fund_transfer_image'));
        }
        $fund_transfer->fund_transfer_image = $fund_transfer_image;
        $fund_transfer->save();

        return true;
    }
}
