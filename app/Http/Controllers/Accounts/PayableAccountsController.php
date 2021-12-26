<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ChartOfAccount;
use App\TransactionDetails;

class PayableAccountsController extends Controller
{
    public function report(){
        $payable_parent_ids = ChartOfAccount::where('parent_id', \payable_chart_id())->pluck('id');
        $payable_accounts = ChartOfAccount::where('balance', '<', 0)
                ->where('owner_type_id', config('app.owner_party'))
                // ->whereIn('parent_id', $payable_parent_ids)
                ->paginate(25);

        return view('admin.accounts.reports.payable_accounts', compact('payable_accounts'));

    }
}
