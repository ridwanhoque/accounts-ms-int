<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ChartOfAccountBalance;

class LedgerBalanceReportController extends Controller
{
    public function report(){
        $ledgers = ChartOfAccountBalance::with('chart_of_account')->orderByDesc('id')->paginate(25);
        return view('admin.accounts.reports.ledger_balance_report', compact('ledgers'));
    }
}