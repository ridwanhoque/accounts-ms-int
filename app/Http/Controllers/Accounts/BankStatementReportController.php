<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ChartOfAccountBalance;

class BankStatementReportController extends Controller
{
    public function report(){
        $ledgers = ChartOfAccountBalance::orderByDesc('id')->paginate(25);
        return view('admin.accounts.reports.ledger_balance_report', compact('ledgers'));
    }
}
