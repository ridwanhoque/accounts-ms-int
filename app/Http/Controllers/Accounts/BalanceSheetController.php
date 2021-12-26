<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Accounts\PaymentVoucher;
use App\Transaction;
use App\TransactionDetails;
use App\ChartOfAccount;

class BalanceSheetController extends Controller
{
    public function report(){
        $assets = ChartOfAccount::with('asset_sum')
                        ->whereNull('parent_id')->where('chart_type_id', 3)
                        ->get();
            
        $liabilities = ChartOfAccount::with('asset_sum')
                        ->whereNull('parent_id')->where('chart_type_id', 4)
                        ->get();


        return view('admin.accounts.reports.balance_sheet', compact('assets', 'liabilities'));
    }
}
