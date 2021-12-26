<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\TransactionDetails;

class IncomeExpenseController extends Controller
{
    public function report()
    {
        $incomes = TransactionDetails::with('chart_of_account')->whereHas('chart_of_account', function ($q) {
            $q->where('chart_type_id', 1);
        })
            ->get();

        $expenses = TransactionDetails::with('chart_of_account')->whereHas('chart_of_account', function ($q) {
            $q->where('chart_type_id', 2);
        })
            ->get();

        return view('admin.accounts.reports.income_expense', compact('incomes', 'expenses'));
    }
}
