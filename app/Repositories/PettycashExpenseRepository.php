<?php
namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\PettycashExpense;
use App\PettycashExpenseDetails;

class PettycashExpenseRepository implements CrudInterface{

    public function index(){

    }

    public function create(){

    }

    public function store($request){

        $date = $request->pettycash_expense_date;
        $total_amount = $request->total_amount;

        $error_message = null;
        $pettycash_expense = $this->save_pettycash_expenses($date, $total_amount);

        foreach($request->pettycash_chart_ids as $key => $pettycash_chart_id){
            $details = [
                'pettycash_expense_id' => $pettycash_expense->id,
                'pettycash_chart_id' => $pettycash_chart_id,
                'purpose' => $request->purpose[$key],
                'amount' => $request->amount[$key]
            ];

            if(!$this->check_chart_unique($details)){
                $error_message = 'You can not add same pettycash head more than once!';
            }else{
                $this->save_pettycash_expense_details($details);
            }
            
        }

        if($error_message != null){
            return $error_message;
        }else{
            return 'success';
        }

        
    }

    public function check_chart_unique($details){
        $expense_chart = PettycashExpenseDetails::where([
            'pettycash_expense_id' => $details['pettycash_expense_id'],
            'pettycash_chart_id' => $details['pettycash_chart_id']
        ]);

        if($expense_chart->count() > 0){
            return false;
        }else{
            return true;
        }


    }

    public function show($pettycashExpense){

    }

    public function edit($pettycashExpense){

    }

    public function update($request, $pettycashExpense){

        
    }

    public function destroy($pettycashExpense){
        foreach($pettycashExpense->pettycash_expense_details as $details){
            $details->delete();
        }

        $pettycashExpense->delete();

        return true;
    }

    public function save_pettycash_expenses($date, $total_amount){
        $pettycash_expenses = new PettycashExpense;
        $pettycash_expenses->pettycash_expense_date = $date;
        $pettycash_expenses->total_amount = $total_amount;
        $pettycash_expenses->save();

        return $pettycash_expenses;
    }

    public function save_pettycash_expense_details($details){
        $pettycash_expense_details = new PettycashExpenseDetails;
        $pettycash_expense_details->pettycash_expense_id = $details['pettycash_expense_id'];
        $pettycash_expense_details->pettycash_chart_id = $details['pettycash_chart_id'];
        $pettycash_expense_details->purpose = $details['purpose'];
        $pettycash_expense_details->amount = $details['amount'];
        $pettycash_expense_details->save();

        return $pettycash_expense_details;
    }

}