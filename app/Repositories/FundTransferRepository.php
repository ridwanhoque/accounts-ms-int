<?php
namespace App\Repositories;

use App\FundTransfer;
use App\Interfaces\CrudInterface;
use App\Transection;
use Uploader;

class FundTransferRepository implements CrudInterface
{
    public function index()
    {

    }

    public function create()
    {

    }

    public function store($request)
    {
        //store into fund transfer table
        $save_fund_transfers = $this->save_fund_transfers($request);

        //deduct amount from payers account
        $this->deduct_transactions($request, $save_fund_transfers);

        //add amount to payee account
        $this->add_transactions($request, $save_fund_transfers);

        //add amount to receivers account
        $this->add_account_balance($request->to_account_id, $request->amount);

        //deduct amount from payers account
        $this->deduct_account_balance($request->from_account_id, $request->amount);


        return true;
    }

    public function show($fund_transfer)
    {

    }

    public function edit($fund_transfer)
    {

    }

    public function update($request, $fund_transfer)
    {

    }

    public function destroy($fund_transfer)
    {

        $this->delete_from_transactions($fund_transfer);

        return $fund_transfer->delete();
    }

    public function save_fund_transfers($request)
    {
        $fund_transfer = new FundTransfer;
        $fund_transfer->from_account_id = $request->from_account_id;
        $fund_transfer->to_account_id = $request->to_account_id;
        $fund_transfer->fund_transfer_date = $request->fund_transfer_date;
        $fund_transfer->amount = $request->amount;
        $fund_transfer->description = $request->description;
        // $fund_transfer->fund_transfer_image = $request->file('fund_transfer_image')->hashName();
        //image path save
        $fund_transfer_image = '';
        if($request->has('fund_transfer_image')){
            $fund_transfer_image = Uploader::upload_image('images/fund_transfers', $request->file('fund_transfer_image'));
        }
        $fund_transfer->fund_transfer_image = $fund_transfer_image;
        $fund_transfer->save();

        return $fund_transfer;
    }

    public function deduct_transactions($request, $fund_transfer)
    {
        $transaction = new Transection;
        $transaction->account_information_id = $request->from_account_id;
        $transaction->amount = $request->amount * (-1);
        $transaction->transactionable()->associate($fund_transfer);
        $transaction->save();

        return $transaction;
    }

    public function add_transactions($request, $fund_transfer)
    {
        $transaction = new Transection;
        $transaction->account_information_id = $request->to_account_id;
        $transaction->amount = $request->amount;
        $transaction->transactionable()->associate($fund_transfer);
        $transaction->save();

        return $transaction;
    }

    public function delete_from_transactions($fund_transfer)
    {
        //return amount to payers account
        $this->add_account_balance($fund_transfer->from_account_id, $fund_transfer->amount);

        //deduct added amount to receivers account
        $this->deduct_account_balance($fund_transfer->to_account_id, $fund_transfer->amount);

        $transactions = Transection::where([
            'transactionable_type' => 'App\FundTransfer',
            'transactionable_id' => $fund_transfer->id
            ])
            ->WhereIn('account_information_id', [$fund_transfer->from_account_id, $fund_transfer->to_account_id])->get();
        foreach ($transactions as $transaction) {
            $transaction->delete();
        }
        return true;
    }

    public function add_account_balance($account_information_id, $amount){
        $account_information = AccountInformation::find($account_information_id);
        $account_information->account_balance += $amount;
        $account_information->save();

        return $account_information;
    }

    public function deduct_account_balance($account_information_id, $amount){
        $account_information = AccountInformation::find($account_information_id);
        $account_information->account_balance -= $amount;
        $account_information->save();

        return $account_information;
    }

}
