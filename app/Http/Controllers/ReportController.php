<?php

namespace App\Http\Controllers;

use App\ChartOfAccount;
use App\Voucher;
use App\VoucherAccountChart;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function reportPage(){

        $reports = Voucher::with('voucher_account_charts')->orderBy('id','desc')->get();

        return view('admin.accounting.report.income_expense',compact('reports'));
    }

    public function searchReport(Request $request){

        $data = [];
        $data['start_date'] = $request->start_date;
        $data['end_date'] = $request->end_date;
        $data['income_reports'] = Voucher::with('voucher_account_charts','voucher_chart_payment','voucher_payment','party','user')->where('voucher_type','credit')->whereBetween('voucher_date',[$data['start_date'], $data['end_date']])->orderBy('id','desc')->get();
        $data['expense_reports'] = Voucher::with('voucher_account_charts','voucher_chart_payment','voucher_payment','party','user')->where('voucher_type','debit')->whereBetween('voucher_date',[$data['start_date'], $data['end_date']])->orderBy('id','desc')->get();

        return view('admin.accounting.report.income_expense',$data);

    }


}
