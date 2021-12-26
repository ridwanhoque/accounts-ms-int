<?php

namespace App\Http\Controllers;

use App\AccountInformation;
use App\ChartOfAccount;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CashFlowController extends Controller
{
    public function report(Request $request)
    {

        $currentUser = Auth::user();
        $tomorrow = Carbon::now()->tomorrow();
        $today = substr(Carbon::now()->today(), 0, 10);
        
        $this_month = Carbon::now()->startOfMonth();
        $this_month_start = $this_month->toDateString();
        $this_month_name = $this_month->format('F Y');

        $previous_month = Carbon::now()->startOfMonth()->subMonth();
        $previous_month_name = $previous_month->format('F Y');

        $accounts = AccountInformation::accountInfo();

        $filter['account_information_id'] = $request->filled('account_information_id') ? $request->account_information_id : 0;
        $filter['start_date'] = $request->filled('start_date') ? $request->start_date.' 00:00:00' : $this_month;
        $filter['end_date'] = $request->filled('end_date') ? $request->end_date.' 23:59:59' : $tomorrow;

        $cash_flow = ChartOfAccount::with(['cash_flow' => function ($q) use ($filter) {
            if($filter['account_information_id'] > 0){
                $q->where('transections.account_information_id', $filter['account_information_id']);
            }
            $q->whereBetween('transections.created_at', [ $filter['start_date'], $filter['end_date'] ]);
        }])
            ->paginate(25);

        return view('admin.accounting.report.cash_flow', compact([
            'this_month_name', 'previous_month_name', 'cash_flow', 'accounts', 'this_month_start', 'today', 'currentUser'
        ]));
    }
}
