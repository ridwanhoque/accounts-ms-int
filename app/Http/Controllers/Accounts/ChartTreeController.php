<?php

namespace App\Http\Controllers\Accounts;

use App\ChartOfAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\DropdownTrait;

class ChartTreeController extends Controller
{

    use DropdownTrait;

    public function index(){
        
        // dump(strip_tags($this->chartOfAccountCombo()));

        $chart_tree =ChartOfAccount::orderBy('account_code')->get();
        return view('admin.reports.chart_tree', compact('chart_tree'));
    }
}
