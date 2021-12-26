<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transection;
use Carbon\Carbon;
use App\Voucher;
use App\ChartOfAccount;
use Auth;

class IncomeStatementController extends Controller
{
    public function report(){

        $current_user = Auth::user();

        $this_month = Carbon::now()->startOfMonth();
        $this_month_name = $this_month->format('F Y');

        $previous_month = Carbon::now()->startOfMonth()->subMonth();
        $previous_month_name = $previous_month->format('F Y');

        $income_statements = ChartOfAccount::with('voucher_account_charts')
            ->where('company_id', $current_user->company_id)
            ->where('type', 'income')
            ->get();
            // ->whereHas('voucher_account_charts', function($qry){
            //     $qry->whereHas('transactions', function($q){
            //         $q->where('amount', '>', 0);
            //     });
            // })
            // ->get();

        // dd($is);
        return view('admin.accounting.report.income_statement', compact([
            'this_month', 'this_month_name', 'previous_month', 'previous_month_name', 'income_statements'
        ]));
    }
}
