<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Voucher;

class AccountsPayableController extends Controller
{
    public function report(){
        $this_month = Carbon::now()->startOfMonth();
        $this_month_name = $this_month->format('F Y');

        $previous_month = Carbon::now()->startOfMonth()->subMonth();
        $previous_month_name = $previous_month->format('F Y');
        
        $receivable_amounts = Voucher::where('due_amount', '>', 0)
                ->where('voucher_type', 'debit')
                ->groupBy('party_id')
                ->selectRaw('sum(due_amount) as dd, party_id')
                ->get();
                
        return view('admin.accounting.report.accounts_payable', compact([
            'receivable_amounts','this_month_receivables', 'this_month_name', 'previous_month_receivables', 'previous_month_name'
        ]));
    }
}