<?php
namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\PettycashDeposit;
use App\Transection;
use App\AccountInformation;
use Auth;

class PettycashDepositRepository implements CrudInterface
{
    public function index()
    {

    }

    public function create()
    {

    }

    public function store($request)
    {

        //store into pettycash deposit table
        $pettycash_deposit = $this->save_pettycash_deposits($request->except('account_balance'));

        //store into transaction table
        $this->deduct_transactions($request->except('account_balance'), $pettycash_deposit);

        //get default account
        $default_account = $this->get_default_account();

        //store into transaction table
        $this->add_transactions($request->except('account_balance'), $pettycash_deposit, $default_account);

        //add to the receivers account balance
        $this->add_account_balance($request->except('account_balance'), $default_account);

        //deduct from the payers account balance
        $this->deduct_account_balance($request->except('account_balance'));

        

        return 'success';
    }

    public function show($pettycash_deposit)
    {

    }

    public function edit($pettycash_deposit)
    {

    }

    public function update($request, $pettycash_deposit)
    {

    }

    public function destroy($pettycash_deposit)
    {
        $this->delete_transactions($pettycash_deposit);

        $pettycash_deposit = $pettycash_deposit->delete();

        return $pettycash_deposit;

    }

    public function save_pettycash_deposits($data)
    {
        $save_pettycash_deposits = PettycashDeposit::create($data);

        return $save_pettycash_deposits;
    }

    public function deduct_transactions($data, $pettycash_deposit, $default_account = null)
    {
        $transactions = new Transection;
        $transactions->amount = $data['amount'] * (-1);
        $transactions->account_information_id = $data['account_information_id'];
        $transactions->transactionable()->associate($pettycash_deposit);
        $transactions->save();

        return $transactions;
    }

    public function add_transactions($data, $pettycash_deposit, $default_account)
    {
        if ($default_account != null) {

            $transactions = new Transection;
            $transactions->amount = $data['amount'];
            $transactions->account_information_id = $default_account;
            $transactions->transactionable()->associate($pettycash_deposit);
            $transactions->save();

            return $transactions;

        }
    }

    public function delete_transactions($pettycash_deposit)
    {
        $transaction = Transection::where([
            'transactionable_type' => 'App\PettycashDeposit',
            'transactionable_id' => $pettycash_deposit->id,
        ])->first();

        $transaction->delete();

        return $transaction;

    }

    public function get_default_account()
    {
        $default_account = null;

        $currentUser = Auth::user();
        $default_account_exists = AccountInformation::where([
            'company_id' => $currentUser->company_id,
            'default_account' => 1,
        ]);

        if ($default_account_exists->count() > 0) {
            $default_account = $default_account_exists->first()->id;
        }

        return $default_account;
    }

    public function add_account_balance($data, $account_information_id = null){
        $account_information = AccountInformation::find($account_information_id);
        $account_information->account_balance += $data['amount'];
        $account_information->save();

        return $account_information;
    }

    public function deduct_account_balance($data, $account_information_id = null){
        $account_information_id = $data['account_information_id'];
        $account_information = AccountInformation::find($account_information_id);
        $account_information->account_balance -= $data['amount'];
        $account_information->save();

        return $account_information;
    }

}
