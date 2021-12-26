<?php

namespace App\Repositories\Accounts;

use App\Accounts\ContraVoucher;
use App\ChartOfAccount;
use App\ChartOfAccountBalance;
use App\Interfaces\CrudInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use App\Transaction;


//use Your Model

/**
 * Class ContraVoucherRepository.
 */
class ContraVoucherRepository implements CrudInterface
{
    public function index()
    {
        return ContraVoucher::paginate(25);
    }

    public function create()
    {
        $data['chart_of_accounts'] = ChartOfAccount::noChild()
			->where('company_id', companyId())
            ->where('owner_type_id', config('app.cash_bank_ids'))
            ->pluck('head_name', 'id');

        $data['bank_cash_charts'] = ChartOfAccount::noChild()
			->where('company_id', companyId())
            ->whereIn('owner_type_id', config('app.cash_bank_ids'))
            ->pluck('head_name', 'id');

        return $data;
    }


    public function save_contra_voucher($request){

        $data = [
            'contra_date' => $request->contra_date,
            'ref_id' => $request->ref_no,
            'note' => $request->note,
            'amount' => $request->total_credit_amount
        ];

        $contra_voucher = ContraVoucher::create($data);

        return $contra_voucher;
    }

    public function save_contra_voucher_details($contra_voucher, $chart_id, $chart_description, $chart_amount){
        
        $data = [
            'chart_of_account_id' => $chart_id,
            'description' => $chart_description,
            'amount' => $chart_amount
        ];

        $contra_voucher_details_id = $contra_voucher->contra_voucher_details()->create($data)->id;

        return $contra_voucher_details_id;
    }

    public function save_credit_chart_of_account_balance($credit_chart_id, $credit_amount){
        $data = [
            'date' => Carbon::today()->format('Y-m-d'),
            'chart_of_account_id' => $credit_chart_id,
            'opening_balance' => $credit_amount,
            'closing_balance' => $credit_amount
        ];

        $chart_of_account = ChartOfAccountBalance::create($data);

        return $chart_of_account;

    }

    public function save_credit_chart_of_account($credit_chart_id, $credit_amount){
       
            $chart_of_account = ChartOfAccount::find($credit_chart_id);
            $chart_of_account->increment('balance', $credit_amount);

        return true;
    }

    public function save_debit_chart_of_account_balance($debit_chart_id, $debit_amount){
        $data = [
            'date' => Carbon::today()->format('Y-m-d'),
            'chart_of_account_id' => $debit_chart_id,
            'opening_balance' => $debit_amount,
            'closing_balance' => $debit_amount
        ];

        $chart_of_account = ChartOfAccountBalance::create($data);

        return $chart_of_account;

    }

    public function save_debit_chart_of_account($debit_chart_id, $debit_amount){
               
            $chart_of_account = ChartOfAccount::find($debit_chart_id);
            $chart_of_account->decrement('balance', $debit_amount);

        return true;
    }

    public function save_transaction($contra_voucher, $request){
        $transaction = new Transaction;
        $transaction->transaction_date = $request->contra_date;
        $transaction->amount = $request->total_credit_amount;
        $transaction->transactionable()->associate($contra_voucher);
        $transaction->save();

        return $transaction;
    }

    public function save_transaction_details($transaction, $chart_id, $chart_amount, $is_credit = NULL)
    {
        $transaction->transaction_details()->create([
            'chart_of_account_id' => $chart_id,
            'amount' => $is_credit == 1 ? $chart_amount*(-1):$chart_amount
        ]);

        return true;
    }

    public function store($request)
    { 
        DB::transaction(function () use($request) {
            
            //store voucher information into contra voucher table
            $contra_voucher = $this->save_contra_voucher($request);


            //store data into transaction table     
            $transaction = $this->save_transaction($contra_voucher, $request);
            
            foreach($request->credit_chart_ids as $key => $credit_chart_id){

                $credit_description = $request->credit_description[$key];
                $credit_amount = $request->credit_amount[$key];
                
                //store voucher information into contra voucher details table
                $this->save_contra_voucher_details($contra_voucher, $credit_chart_id, $credit_description, $credit_amount);

                //update chart_of account current balance
                $this->save_credit_chart_of_account($credit_chart_id, $credit_amount);

                //store datewise debit credit into chart of account balance table
                $this->save_credit_chart_of_account_balance($credit_chart_id, $credit_amount);

                //store into transaction details table
                $this->save_transaction_details($transaction, $credit_chart_id, $credit_amount, 1);

            }

            foreach($request->debit_chart_ids as $debit_key => $debit_chart_id){

                $debit_description = $request->debit_description[$debit_key];
                $debit_amount = $request->debit_amount[$debit_key];
                
                //store voucher information into contra voucher details table
                $this->save_contra_voucher_details($contra_voucher, $debit_chart_id, $debit_description, $debit_amount);

                //update chart_of account current balance
                $this->save_debit_chart_of_account($debit_chart_id, $debit_amount);

                //store datewise debit credit into chart of account balance table
                $this->save_debit_chart_of_account_balance($debit_chart_id, $debit_amount);

                //store into transaction details table
                $this->save_transaction_details($transaction, $debit_chart_id, $debit_amount);

            }
            



        });
 
        return true;

    }

    public function show($id)
    { 
        $contra_voucher = ContraVoucher::find($id);

        return $contra_voucher;

    }

    public function edit($model)
    { }

    public function update($request, $model)
    { }

    public function destroy($model)
    { }
}
