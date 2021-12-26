<?php

namespace App\Http\Controllers;

use App\TransactionDetails;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Voucher;

class AccountsReceivableController extends Controller
{
    public function report(){
        
        $this_month = Carbon::now()->startOfMonth();
        $this_month_name = $this_month->format('F Y');

        $previous_month = Carbon::now()->startOfMonth()->subMonth();
        $previous_month_name = $previous_month->format('F Y');
        
        // $receivable_amounts = Voucher::where('due_amount', '>', 0)
        //         ->where('voucher_type', 'credit')
        //         ->groupBy('party_id')
        //         ->selectRaw('sum(due_amount) as dd, party_id')
        //         ->get();

        $receivable_amounts = TransactionDetails::where('amount', '<', 0)
            ->whereHas('chart_of_account', function($q){
                $q->where('owner_type_id', 1);
            })
            ->get();

        // dd($receivable_amounts);

        return view('admin.accounting.report.accounts_receivable', compact([
            'receivable_amounts','this_month_receivables', 'this_month_name', 'previous_month_receivables', 'previous_month_name'
        ]));
    }
}
