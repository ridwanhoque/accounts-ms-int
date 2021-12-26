<?php

namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\Purchase;
use App\PurchaseDetails;
use App\RawMaterialStock;
use App\Transection;
use App\AccountInformation;
use App\Transaction;
use App\ChartOfAccount;
use Carbon\Carbon;
use App\ChartOfAccountBalance;
use Auth;
use DB;

class PurchaseRepository implements CrudInterface
{
    public function index()
    {
        return Purchase::orderByDesc('id')->where('company_id', companyId())
                    ->with([
                        'purchase_details', 'batch', 'party', 'chart_of_account'
                    ])->paginate(25);
    }

    public function create()
    { }

    public function transaction_details_save($request, $transaction, $key)
    {
        $amount = $request->total_payable;
        $transaction->transaction_details()->create([
            'chart_of_account_id' => $request->chart_of_account_ids[$key],
            'amount' => $key == 1 ? $amount:$amount*(-1)
        ]);

        return true;
    }

    public function save_chart_of_account($chart_id, $amount, $key){
        $chart_of_account = ChartOfAccount::find($chart_id);
        $key == 1 ? $chart_of_account->increment('balance', $amount):$chart_of_account->decrement('balance', $amount);
        
        if($chart_of_account->parent_id > 0){
            $this->save_chart_of_account($chart_of_account->parent_id, $amount, $key);
        }
        return true;
    }

    public function save_chart_of_account_balance($request, $key){
    $data = [
        'date' => Carbon::today()->format('Y-m-d'),
        'chart_of_account_id' => $request->chart_of_account_ids[$key],
        'opening_balance' => $request->total_payable,
        'closing_balance' => $request->total_payable
    ];

    $chart_of_account = ChartOfAccountBalance::create($data);

    return $chart_of_account;

}

    public function transaction_save($request, $purchase)
    {
        $transaction = new Transaction;
        $transaction->transaction_date = $request->purchase_date;
        $transaction->amount = $request->total_payable;
        $transaction->transactionable()->associate($purchase);
        $transaction->save();

        foreach ($request->chart_of_account_ids as $key => $chart) {
            //store data into transaction details
            $this->transaction_details_save($request, $transaction, $key);

            //store data into 
            $this->save_chart_of_account($chart, $request->total_payable, $key);

            //store data into daily chart of account
            $this->save_chart_of_account_balance($request, $key);

        }
        
        return true;
    }

    public function store($request)
    {
        DB::transaction(function () use($request) {
            $purchase = new Purchase;
            $purchase->batch_id = $request->batch_id;
            $purchase->party_id = $request->party_id;
            $purchase->status_id = $request->status_id;
            $purchase->purchase_date = $request->purchase_date;
            $purchase->sub_total = $request->sub_total;
            $purchase->invoice_discount = $request->invoice_discount;
            $purchase->invoice_tax = $request->invoice_tax;
            $purchase->tax_percent = $request->tax_percent;
            $purchase->total_payable = $request->total_payable;
            $purchase->chart_of_account_id = $request->chart_of_account_ids[0];
            $purchase->save();
    
            foreach ($request->sub_raw_material_ids as $key => $rm_id) {
                $purchase_details = new PurchaseDetails;
                $purchase_details->purchase_id = $purchase->id;
                $purchase_details->sub_raw_material_id = $rm_id;
                $purchase_details->unit_price = $request->price[$key];
                $purchase_details->quantity = $request->qty[$key];
                $purchase_details->raw_material_sub_total = $request->total[$key];
                $purchase_details->save();
            }
    
            //hit in accounts 
            $this->transaction_save($request, $purchase);
    
        });
    

        return true;
    }

    public function show($purchase)
    { }

    public function edit($purchase)
    { }

    public function update($request, $purchase)
    {
        $purchase->batch_id = $request->batch_id;
        $purchase->party_id = $request->party_id;
        $purchase->status_id = $request->status_id;
        $purchase->purchase_date = $request->purchase_date;
        $purchase->purchase_reference = $request->purchase_reference;
        $purchase->sub_total = $request->sub_total;
        $purchase->invoice_discount = $request->invoice_discount;
        $purchase->invoice_tax = $request->invoice_tax;
        $purchase->tax_percent = $request->tax_percent;
        $purchase->total_payable = $request->total_payable;
        $purchase->chart_of_account_id = $request->chart_of_account_ids[0];
        $purchase->save();


        foreach ($request->sub_raw_material_ids as $key => $rm_id) {

            $purchase_details = PurchaseDetails::findOrFail($request->purchase_details_id);
            $purchase_details->purchase_id = $request->id;
            $purchase_details->sub_raw_material_id = $rm_id;
            $purchase_details->unit_price = $request->price[$key];
            $purchase_details->quantity = $request->qty[$key];
            $purchase_details->raw_material_sub_total = $request->total[$key];
            $purchase_details->save();
        }

        // //hit in accounts 
        // $this->transaction_save($request, $purchase);


        return true;
    }

    public function destroy($purchase)
    {
        $purchase_receives = $purchase->purchase_receives->count();
        if ($purchase_receives > 0) {
            return ['error_message' => 'This purchase already received!'];
        } else {
            // $this->purchase_delete($purchase);
            // $this->transaction_delete($purchase);
            // $purchase->transactions()->detach();
            $purchase->delete();
            // return true;
        }
    }

    public function transaction_delete($purchase){
        foreach($purchase->transactions as $ptransaction){
                foreach($ptransaction->transaction_details as $tdetails){
                    $tdetails->delete;
                }
                $ptransaction->delete();
        }


        return true;
    }


    // public function save_transection($request, $purchase)
    // {

    //     $this_default_account = $this->this_default_account();
    //     $transaction = new Transection;
    //     $transaction->amount = $request->total_paid * (-1);
    //     $transaction->account_information_id = $this_default_account;
    //     $transaction->transactionable()->associate($purchase);
    //     $transaction->save();

    //     return $transaction;
    // }

    // public function this_default_account()
    // {
    //     $current_user = Auth::user();
    //     $this_default_account = AccountInformation::where([
    //         'company_id' => $current_user->company_id,
    //         'default_account' => 1
    //     ])->first()->id;

    //     return $this_default_account;
    // }
}
