<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LedgerReportConrtoller extends Controller
{
    public function report(){
        $assets = TransactionDetails::whereHas('chart_of_account', function($q){
                        $q->where('chart_type_id', 3);
                    })
                    ->get();
            
        $liabilities = TransactionDetails::whereHas('chart_of_account', function($q){
                        $q->where('chart_type_id', 4);
                    })
                    ->get();


        return view('admin.accounts.reports.balance_sheet', compact('assets', 'liabilities'));
    }
}
