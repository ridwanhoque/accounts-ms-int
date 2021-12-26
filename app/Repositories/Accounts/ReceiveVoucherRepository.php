<?php

namespace App\Repositories\Accounts;

use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Accounts\ReceiveVoucher;
use App\ChartOfAccount;
use App\ChartOfAccountBalance;
use App\Interfaces\CrudInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use App\Transaction;

/**
 * Class ReceiveVoucherRepository.
 */
class ReceiveVoucherRepository implements CrudInterface
{
    public function index()
    {
        return ReceiveVoucher::paginate(25);
    }

    public function create()
    {
        $data['bank_cash_charts'] = ChartOfAccount::noChild()
			->where('company_id', auth()->user()->id)
            //owner party can be uncommented if need
            // ->where('owner_type_id', config('app.owner_party'))
            // ->where('balance', '>', 0)
            ->pluck('head_name', 'id');

        $data['chart_of_accounts'] = ChartOfAccount::noChild()
			->where('company_id', auth()->user()->id)
            ->whereIn('owner_type_id', config('app.cash_bank_ids'))
            ->pluck('head_name', 'id');

        return $data;
    }


    public function save_receive_voucher($request){

        $data = [
            'receive_date' => $request->receive_date,
            'ref_id' => $request->ref_no,
            'note' => $request->note,
            'amount' => $request->total_credit_amount
        ];

        $receive_voucher = ReceiveVoucher::create($data);

        return $receive_voucher;
    }

    public function save_receive_voucher_details($receive_voucher, $chart_id, $chart_description, $chart_amount){
        
        $data = [
            'chart_of_account_id' => $chart_id,
            'description' => $chart_description,
            'amount' => $chart_amount
        ];

        $receive_voucher_details_id = $receive_voucher->receive_voucher_details()->create($data)->id;

        return $receive_voucher_details_id;
    }

    public function save_credit_chart_of_account_balance($credit_chart_id, $credit_amount){
        $previous_closing_balance = ChartOfAccountBalance::orderByDesc('date')->where('chart_of_account_id', $credit_chart_id)->skip(1)->take(1)->get();
        $chart_opening_balance = ChartOfAccount::find($credit_chart_id)->opening_balance;
        $previous_opening_balance = $previous_closing_balance->first()->closing_balance ?? $chart_opening_balance;
        $today_exists = ChartOfAccountBalance::where('date', Carbon::today())->where('chart_of_account_id', $credit_chart_id);
        if($today_exists->count() > 0){
            $chart_of_account_balance = $today_exists->first();
            if($chart_of_account_balance->opening_balance == 0){
                $chart_of_account_balance->opening_balance = $previous_opening_balance ?? $credit_amount;
            }
            $chart_of_account_balance->chart_of_account_id = $credit_chart_id;
            $chart_of_account_balance->credit_amount += $credit_amount;
            $chart_of_account_balance->closing_balance -= $credit_amount;
            $chart_of_account_balance->save();
        }else{
            $data = [
                'date' => Carbon::today()->format('Y-m-d'),
                'chart_of_account_id' => $credit_chart_id,
                'opening_balance' => $credit_amount,
                'credit_amount' => $credit_amount,
                'closing_balance' => $credit_amount*(-1)
            ];
    
            $chart_of_account_balance = ChartOfAccountBalance::create($data);    
        }

        return $chart_of_account_balance;
    }

    public function save_credit_chart_of_account($credit_chart_id, $credit_amount){
       
            $chart_of_account = ChartOfAccount::find($credit_chart_id);
            $chart_of_account->decrement('balance', $credit_amount);

        return true;
    }

    public function save_credit_chart_of_account_parents($credit_chart_id, $credit_amount){
        $parent_chart = ChartOfAccount::find($credit_chart_id);
        $parent_chart->decrement('balance',$credit_amount);

        if($parent_chart->parent_id > 0){
            $this->save_credit_chart_of_account_parents($parent_chart->parent_id, $credit_amount);
        }

        return true;
    }


    public function save_debit_chart_of_account_balance($debit_chart_id, $debit_amount){
        $previous_closing_balance = ChartOfAccountBalance::orderByDesc('date')->where('chart_of_account_id', $debit_chart_id)->skip(1)->take(1)->get();
        $chart_opening_balance = ChartOfAccount::find($debit_chart_id)->opening_balance;
        $previous_opening_balance = $previous_closing_balance->first()->closing_balance ?? $chart_opening_balance;
        $today_exists = ChartOfAccountBalance::where('date', Carbon::today())->where('chart_of_account_id', $debit_chart_id);
        if($today_exists->count() > 0){
            $chart_of_account_balance = $today_exists->first();
            if($chart_of_account_balance->opening_balance == 0){
                $chart_of_account_balance->opening_balance = $previous_opening_balance ?? $debit_amount;
            }
            $chart_of_account_balance->chart_of_account_id = $debit_chart_id;
            $chart_of_account_balance->debit_amount += $debit_amount;
            $chart_of_account_balance->closing_balance += $debit_amount;
            $chart_of_account_balance->save();
        }else{
            $data = [
                'date' => Carbon::today()->format('Y-m-d'),
                'chart_of_account_id' => $debit_chart_id,
                'opening_balance' => $debit_amount,
                'debit_amount' => $debit_amount,
                'closing_balance' => $debit_amount
            ];
    
            $chart_of_account_balance = ChartOfAccountBalance::create($data);    
        }

        return $chart_of_account_balance;

    }

    public function save_debit_chart_of_account($debit_chart_id, $debit_amount){
               
            $chart_of_account = ChartOfAccount::find($debit_chart_id);
            $chart_of_account->increment('balance', $debit_amount);

        return true;
    }
    
    public function save_debit_chart_of_account_parents($debit_chart_id, $debit_amount){
        $parent_chart = ChartOfAccount::find($debit_chart_id);
        $parent_chart->increment('balance', $debit_amount);

        if($parent_chart->parent_id > 0){
            $this->save_debit_chart_of_account_parents($parent_chart->parent_id, $debit_amount);
        }

        return true;
    }


    public function save_transaction($receive_voucher, $request){
        $transaction = new Transaction;
        $transaction->transaction_date = $request->receive_date;
        $transaction->amount = $request->total_credit_amount;
        $transaction->transactionable()->associate($receive_voucher);
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
            
            //store voucher information into receive voucher table
            $receive_voucher = $this->save_receive_voucher($request);


            //store data into transaction table     
            $transaction = $this->save_transaction($receive_voucher, $request);
            
            foreach($request->credit_chart_ids as $key => $credit_chart_id){

                $credit_description = $request->credit_description[$key];
                $credit_amount = $request->credit_amount[$key];
                
                //store voucher information into receive voucher details table
                $this->save_receive_voucher_details($receive_voucher, $credit_chart_id, $credit_description, $credit_amount);

                //update chart_of account current balance
                $this->save_credit_chart_of_account_parents($credit_chart_id, $credit_amount);

                //store datewise debit credit into chart of account balance table
                $this->save_credit_chart_of_account_balance($credit_chart_id, $credit_amount);

                //store into transaction details table
                $this->save_transaction_details($transaction, $credit_chart_id, $credit_amount, 1);

            }

            foreach($request->debit_chart_ids as $debit_key => $debit_chart_id){

                $debit_description = $request->debit_description[$debit_key];
                $debit_amount = $request->debit_amount[$debit_key];
                
                //store voucher information into receive voucher details table
                $this->save_receive_voucher_details($receive_voucher, $debit_chart_id, $debit_description, $debit_amount);

                //update chart_of account current balance
                $this->save_debit_chart_of_account_parents($debit_chart_id, $debit_amount);

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
        $receive_voucher = ReceiveVoucher::find($id);

        return $receive_voucher;

    }

    public function edit($model)
    { }

    public function update($request, $model)
    { }

    public function destroy($model)
    { }
}
