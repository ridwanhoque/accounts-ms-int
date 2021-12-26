<?php
namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\Sale;
use App\SaleDetails;
use App\Transaction;
use App\ChartOfAccount;
use App\ChartOfAccountBalance;
use Carbon\Carbon;
use DB;

class SaleRepository implements CrudInterface
{
    public function index()
    {
        return Sale::with('sale_details')->get();
    }

    public function create()
    {

    }

    public function transaction_details_save($request, $transaction, $key)
    {
        $amount = $request->total_payable;
        $transaction->transaction_details()->create([
            'chart_of_account_id' => $request->chart_of_account_ids[$key],
            'amount' => $key != 1 ? $amount : $amount * (-1),
        ]);
        
        return false;
    }

    public function save_chart_of_account($request, $key, $chart_id = null)
    {
        $chart_id = $chart_id != null ? $chart_id : $request->chart_of_account_ids[$key];
        $chart_of_account = ChartOfAccount::find($chart_id);
        $key == 1 ? $chart_of_account->decrement('balance', $request->total_payable) : $chart_of_account->increment('balance', $request->total_payable);

        if($chart_of_account->parent_id > 0){
            $this->save_chart_of_account($request, $key, $chart_of_account->parent_id);
        }

        return true;
    }

    public function save_chart_of_account_balance($request, $key)
    {
        $data = [
            'date' => Carbon::today()->format('Y-m-d'),
            'chart_of_account_id' => $request->chart_of_account_ids[$key],
            'opening_balance' => $request->total_payable,
            'closing_balance' => $request->total_payable,
        ];

        $chart_of_account = ChartOfAccountBalance::create($data);

        return $chart_of_account;

    }

    public function transaction_save($request, $sale)
    {
        $transaction = new Transaction;
        $transaction->transaction_date = $request->sale_date;
        $transaction->amount = $request->total_payable;
        $transaction->transactionable()->associate($sale);
        $transaction->save();

        foreach ($request->chart_of_account_ids as $key => $chart) {
            //store data into transaction details
            $this->transaction_details_save($request, $transaction, $key);

            //store data into
            $this->save_chart_of_account($request, $key);

            //store data into daily chart of account
            $this->save_chart_of_account_balance($request, $key);

        }

        return true;
    }

    public function store($request)
    {
        DB::transaction(function () use($request) {
            $sale = new Sale;
            $sale->party_id = $request->party_id;
            $sale->status_id = $request->status_id;
            $sale->sale_date = $request->sale_date;
            $sale->sale_delivery_date = $request->sale_delivery_date;
            $sale->sub_total = $request->sub_total;
            $sale->invoice_vat = $request->invoice_vat;
            $sale->invoice_tax = $request->invoice_tax;
            $sale->tax_percent = $request->tax_percent;
            $sale->total_payable = $request->total_payable;
            $sale->chart_of_account_id = $request->chart_of_account_ids[0];
            $sale->save();
    
            foreach ($request->product_ids as $key => $product_id_db) {
                $sale_details = new SaleDetails;
                $sale_details->sale_id = $sale->id;
                $sale_details->product_id = $product_id_db;
                $sale_details->unit_price = $request->price[$key];
                $sale_details->quantity = $request->qty[$key];
                $sale_details->pack = $request->pack[$key];
                $sale_details->weight = $request->weight[$key];
                $sale_details->product_sub_total = $request->total[$key];
                $sale_details->save();
            }
    
    

            //hit in accounts 
            $this->transaction_save($request, $sale);    
        });
       

        return true;

    }

    public function show($sale)
    {

    }

    public function edit($sale)
    {

    }

    public function update($request, $sale)
    {

        $sale->party_id = $request->party_id;
        $sale->status_id = $request->status_id;
        $sale->sale_date = $request->sale_date;
        $sale->sale_delivery_date = $request->sale_delivery_date;
        $sale->sub_total = $request->sub_total;
        $sale->invoice_vat = $request->invoice_vat;
        $sale->invoice_tax = $request->invoice_tax;
        $sale->tax_percent = $request->tax_percent;
        $sale->total_payable = $request->total_payable;
        $sale->chart_of_account_id = $sale->chart_of_account_ids[0];
        $sale->save();

        foreach ($request->product_ids as $key => $product_id_db) {

            $sale_details = SaleDetails::findOrFail($request->sale_details_id[$key]);
            $sale_details->sale_id = $request->id;
            $sale_details->product_id = $product_id_db;
            $sale_details->unit_price = $request->price[$key];
            $sale_details->quantity = $request->qty[$key];
            $sale_details->product_sub_total = $request->total[$key];
            $sale_details->save();

            //if sale is completed
            // if($request->status_id == 1){
            //     $product_stock_all = ProductStock::where('product_id', $product_id_db);
            //     if($product_stock_all->count() > 0){
            //         $product_stock = $product_stock_all->first();
            //         $product_stock->available_quantity += $request->qty[$key];
            //         $product_stock->sold_quantity += $request->qty[$key];
            //     }else{
            //         $product_stock = new ProductStock;
            //         $product_stock->product_id = $product_id_db;
            //         $product_stock->available_quantity = $request->qty[$key];
            //         $product_stock->sold_quantity = $request->qty[$key];
            //     }
            //     $product_stock->save();
            // }

        }

        return true;
    }



    public function transaction_delete($sale_id)
    {
        $sale = Sale::find($sale_id);
            foreach($sale->transactions as $transaction){
                foreach($transaction->transaction_details as $key => $details){
    
                    //     //store data into daily chart of account
                    //     $this->deduct_chart_of_account_balance($request, $key);
    
                        $chart_of_account = ChartOfAccount::find($details->chart_of_account_id);
                        $key == 1 ? $chart_of_account->decrement('balance', abs($details->amount)) : $chart_of_account->increment('balance', abs($details->amount));
    
                    $details->delete();
                }
                $transaction->delete();
            }
        
        

        return true;
    }

    public function destroy($sale)
    {
        // DB::transaction(function ($sale)
         {

            if($sale->product_delivery_details->count() <= 0){

            $this->transaction_delete($sale->id);

            foreach ($sale->sale_details as $details) {
                $details->delete();
            }
    
            $sale->delete();

        }
                
        }
    // );

        return true;

    }

}
