<?php
 
namespace App\Repositories;

use App\ChartOfAccount;
use App\Interfaces\CrudInterface;
use App\Ledger;
use Illuminate\Support\Facades\Auth;

class LedgerRepository implements CrudInterface{

    public function index()
    {
        
    }

    public function create()
    {
        $currentUser = Auth::user();
        $data['chart_of_accounts'] = ChartOfAccount::where('company_id', $currentUser->company_id)
            ->pluck('head_name', 'id');

        return $data;
    }

    public function save_ledger($request){
        $ledger = Ledger::create([
            'payment_date' => $request->payment_date,
            'ref_id' => $request->ref_id,
            'note' => $request->note
        ]);

        return $ledger;
    }

    public function save_ledger_details($request, $key, $ledger){
        $debit_amount = $request->debit_amount[$key];
        $credit_amount = $request->credit_amount[$key];
        $data = [
            'chart_of_account_id' => $request->chart_of_account_id[$key],
            'amount' => $debit_amount > 0 ? $debit_amount:$credit_amount ,
            'description' => $request->description[$key]
        ];
        $details = $ledger->ledger_details()->create($data);

        return $details;
    }

    public function store($request)
    {
        $ledger = $this->save_ledger($request);

        foreach($request->chart_of_account_id as $key => $chart){
            $this->save_ledger_details($request, $key, $ledger);
        }

        return 'true';

    }

    public function show($model)
    {
        
    }

    public function edit($model)
    {
        
    }

    public function update($request, $model)
    {
        
    }

    public function destroy($model)
    {
        
    }

}