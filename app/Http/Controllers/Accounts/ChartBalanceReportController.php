<?php

namespace App\Http\Controllers\Accounts;

use App\ChartOfAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChartBalanceReportController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index($id = null)
    {

        $chart_of_accounts = ChartOfAccount::orderByDesc('balance')
                                ->when($id == null, function($query){
                                    $query->where('tire', 1);
                                })
                                ->when($id > 0 , function($query) use($id){
                                    $query->where('parent_id', $id);
                                })
                                ->paginate(10);

                                $cols = ['parent_id', 'id', 'tire'];
        $charts_tree = ChartOfAccount::query(function($query) use($cols){
            foreach($cols as $col){
                $query->orderBy($col, 'asc');
            }
        })->pluck('head_name');
                                
        return view('admin.accounting.report.chart_balance', compact('chart_of_accounts', 'charts_tree'));
    }
}
