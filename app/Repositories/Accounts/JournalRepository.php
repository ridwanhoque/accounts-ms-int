<?php

namespace App\Repositories\Accounts;

use App\Interfaces\CrudInterface;
use App\Accounts\Journal;
use App\Repositories\Accounts\JournalRepository;
use App\ChartOfAccount;


/**
 * Class JournalRepository.
 */
class JournalRepository implements CrudInterface
{

    public function index(){
        return Journal::paginate(25);
    }

    public function create(){
        $data['chart_of_accounts'] = ChartOfAccount::where('company_id', companyId())
            ->pluck('head_name', 'id');

        $data['credit_charts'] = ChartOfAccount::where('company_id', companyId())
            ->pluck('head_name', 'id');

        return $data;
    }

    public function save_journal($request){
        
        $journal = Journal::create([
            'journal_date' => $request->journal_date,
            'ref_id' => $request->ref_id,
            'note' => $request->note,
            'amount' => $request->amount
        ]);

        return true;
            
    }

    // public function 

    public function save_debit_chart($request, $key, $journal){
        
    }



    public function store($request){
        $journal = $this->save_journal($request);

        foreach($request->debit_chart_ids as $debit_key => $chart){
            $this->save_debit_chart($request, $debit_key, $journal);
        }

        foreach($request->credit_chart_ids as $credit_key => $chart){
            $this->save_credit_chart($request, $credit_key, $journal);
        }


    }

    public function show($model){

    }

    public function edit($model){

    }

    public function update($request, $model){

    }

    public function destroy($request){

    }

	
	    public function index()
    {
        return PaymentVoucher::paginate(25);
    }

    public function create()
    {
        $data['chart_of_accounts'] = ChartOfAccount::where('company_id', companyId())
            ->where('owner_type_id', config('app.owner_party'))
            ->pluck('head_name', 'id');

        $data['bank_cash_charts'] = ChartOfAccount::where('company_id', companyId())
            ->whereIn('owner_type_id', config('app.cash_bank_ids'))
            ->pluck('head_name', 'id');

        return $data;
    }


    public function save_payment_voucher($request){

        $data = [
            'payment_date' => $request->payment_date,
            'ref_id' => $request->ref_no,
            'note' => $request->note,
            'amount' => $request->total_credit_amount
        ];

        $payment_voucher = PaymentVoucher::create($data);

        return $payment_voucher;
    }

    public function save_payment_voucher_details($payment_voucher, $chart_id, $chart_description, $chart_amount){
        
        $data = [
            'chart_of_account_id' => $chart_id,
            'description' => $chart_description,
            'amount' => $chart_amount
        ];

        $payment_voucher_details_id = $payment_voucher->payment_voucher_details()->create($data)->id;

        return $payment_voucher_details_id;
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

    public function save_transaction($payment_voucher, $request){
        $transaction = new Transaction;
        $transaction->transaction_date = $request->payment_date;
        $transaction->amount = $request->total_credit_amount;
        $transaction->transactionable()->associate($payment_voucher);
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
            
            //store voucher information into payment voucher table
            $payment_voucher = $this->save_payment_voucher($request);


            //store data into transaction table     
            $transaction = $this->save_transaction($payment_voucher, $request);
            
            foreach($request->credit_chart_ids as $key => $credit_chart_id){

                $credit_description = $request->credit_description[$key];
                $credit_amount = $request->credit_amount[$key];
                
                //store voucher information into payment voucher details table
                $this->save_payment_voucher_details($payment_voucher, $credit_chart_id, $credit_description, $credit_amount);

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
                
                //store voucher information into payment voucher details table
                $this->save_payment_voucher_details($payment_voucher, $debit_chart_id, $debit_description, $debit_amount);

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
        $payment_voucher = PaymentVoucher::find($id);

        return $payment_voucher;

    }

    public function edit($model)
    { }

    public function update($request, $model)
    { }

    public function destroy($model)
    { }

	
	
	
	
	
	
	
	
	}
