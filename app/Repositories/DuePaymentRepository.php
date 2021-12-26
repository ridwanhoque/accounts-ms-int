<?php
namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\Transection;
use App\Voucher;
use App\VoucherAccountChart;
use App\VoucherPayment;
use App\VoucherChartPayment;

class DuePaymentRepository implements CrudInterface
{
    public function index()
    {

    }
    public function create()
    {

    }

    public function store($request)
    {
        $data = [
            'voucher_id' => $request->voucher_id,
            'account_information_id' => $request->account_information_id,
            'payment_method_id' => $request->payment_method_id,
            'type' => $request->type,
            'cheque_number' => $request->cheque_number,
            'paid_amount' => $request->paid_amount,
        ];

        //store into voucher payment table
        $voucher_payment = $this->save_voucher_payment($data);

        //store into transaction table
        $this->save_transaction($data, $voucher_payment);

        //update paid and due amount of voucher table
        $this->save_voucher($data);

        //update paid amount of voucher account chart table
        $voucher_account_chart = $this->save_voucher_account_chart($data, $voucher_payment->id);

       

        return true;
    }

    public function show($duePayment)
    {

    }

    public function edit($duePayment)
    {

    }

    public function update($request, $duePayment)
    {

    }

    public function destroy($request)
    {
        $voucher_payment_id = $request->voucher_payment_id;
        //deduct amount from transaction
        $this->delete_transaction($voucher_payment_id);

        //deduct from voucher paid amount
        $this->update_voucher($voucher_payment_id);

        //deduct amount from voucher account chart
        $this->update_voucher_account_chart($voucher_payment_id);


        //delete voucher chart payment
        $voucher_chart_payments = VoucherChartPayment::where([
            'voucher_payment_id' => $voucher_payment_id
            ])->get();

            foreach($voucher_chart_payments as $voucher_chart_payment){
                $voucher_chart_payment->delete();
            }
        

        //delete voucher payment
        $voucher_payment = VoucherPayment::find($voucher_payment_id);
        $voucher_payment->delete();

        return true;

    }

    public function save_voucher_payment($data)
    {
        return VoucherPayment::create($data);
    }

    public function save_transaction($data, $voucher_payment)
    {
        $transaction = new Transection;
        $transaction->account_information_id = $data['account_information_id'];
        $transaction->amount = $data['paid_amount'];
        $transaction->transactionable()->associate($voucher_payment);
        $transaction->save();

        return $transaction;

    }

    public function save_voucher($data)
    {
        $voucher = Voucher::findOrFail($data['voucher_id']);
        $voucher->paid_amount += $data['paid_amount'];
        $voucher->due_amount -= $data['paid_amount'];
        $voucher->save();

        return $voucher;
    }

    public function save_voucher_account_chart($data, $voucher_payment_id)
    {
        $voucher_account_charts = VoucherAccountChart::where('voucher_id', $data['voucher_id'])->get();
        $paid_amount = $data['paid_amount'];
        foreach ($voucher_account_charts as $voucher_account_chart) {
            $due_amount = $voucher_account_chart->payable_amount - $voucher_account_chart->paid_amount;

            if ($paid_amount >= $due_amount) {
                $voucher_account_chart->paid_amount += $due_amount;
                $paid_amount -= $due_amount;
            }else{
                $voucher_account_chart->paid_amount += $paid_amount;                
                $paid_amount = 0;
            }

            

            $voucher_account_chart->save();

            $ids = [
                'voucher_payment_id' => $voucher_payment_id,
                'voucher_account_chart_id' => $voucher_account_chart->id
            ];
            //store data into voucher chart payment table
            $this->save_voucher_chart_payment($data, $ids);


        }

    }

    public function save_voucher_chart_payment($data, $ids){
        $voucher_chart_payment = new VoucherChartPayment;
        $voucher_chart_payment->voucher_id = $data['voucher_id'];
        $voucher_chart_payment->account_information_id = $data['account_information_id'];
        $voucher_chart_payment->payment_method_id = $data['payment_method_id'];
        $voucher_chart_payment->voucher_payment_id = $ids['voucher_payment_id'];
        $voucher_chart_payment->voucher_account_chart_id = $ids['voucher_account_chart_id'];
        $voucher_chart_payment->save();

        return $voucher_chart_payment;
    }

    public function delete_transaction($voucher_payment_id){
        $transaction = Transection::where([
                'transactionable_type' => 'App\VoucherPayment',
                'transactionable_id' => $voucher_payment_id
        ]);
        $transaction->delete();
    }


    public function update_voucher($voucher_payment_id){
        $voucher_payment = VoucherPayment::find($voucher_payment_id);
        
        $voucher = Voucher::findOrFail($voucher_payment->voucher_id);
        $voucher->paid_amount -= $voucher_payment->paid_amount;
        $voucher->due_amount += $voucher_payment->paid_amount;
        $voucher->save();

        return $voucher;
    }

    public function update_voucher_account_chart($voucher_payment_id)
    {
        $voucher_payment = VoucherPayment::find($voucher_payment_id);

        $voucher_account_charts = VoucherAccountChart::where('voucher_id', $voucher_payment->voucher_id)->get();
        $paid_amount = $voucher_payment->paid_amount;

        foreach ($voucher_account_charts as $voucher_account_chart) {
            $due_amount = $voucher_account_chart->payable_amount + $voucher_account_chart->paid_amount;

            if ($paid_amount >= $due_amount) {
                $voucher_account_chart->paid_amount -= $due_amount;
                $paid_amount -= $due_amount;
            }else{
                $voucher_account_chart->paid_amount -= $paid_amount;                
                $paid_amount = 0;
            }

            $voucher_account_chart->save();
        }

    }

}
