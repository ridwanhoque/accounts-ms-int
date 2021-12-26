<?php

namespace App\Http\Controllers;

use App\PettycashExpense;
use App\Transection;
use App\Voucher;
use Auth;
use Carbon\Carbon;

class AccountsDashboardController extends Controller
{
    public function report()
    {
        $current_user = Auth::user();
        $account_balances = Transection::where('company_id', $current_user->company_id)
            ->groupBy('account_information_id')
            ->selectRaw('sum(amount) as sum_amount, account_information_id')->get();
        $income_total = Transection::where('company_id', $current_user->company_id)
            ->where('amount', '>', 0)
            ->sum('amount');
        $expense_total = Transection::where('company_id', $current_user->company_id)
            ->where('amount', '<', 0)
            ->sum('amount');

        $this_month = Carbon::now()->startOfMonth();

        $this_month_year = $this_month->format('F Y');

        $income_this_month = Transection::TransectionInfo()
            ->where('amount', '>', 0)
            ->where('created_at', '>=', $this_month)
            ->sum('amount');

        $expense_this_month = Transection::TransectionInfo()
            ->where('amount', '<', 0)
            ->where('created_at', '>=', $this_month)
            ->sum('amount');

        $pettycash_expenses = PettycashExpense::PettycashExpenseInfo()
            ->sum('total_amount');

        $receivable_amount = Voucher::where([
            'company_id' => $current_user->company_id,
            'voucher_type' => 'credit',
        ])->sum('due_amount');

        $payable_amount = Voucher::where([
            'company_id' => $current_user->company_id,
            'voucher_type' => 'debit'
        ])->sum('due_amount');

        return view('admin.accounting.report.accounts_dashboard', compact([
            'account_balances', 'income_total', 'expense_total', 'this_month_year',
            'income_this_month', 'expense_this_month', 'pettycash_expenses',
            'receivable_amount', 'payable_amount'
        ]));
    }
}
