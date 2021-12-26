<?php

namespace App\Http\Controllers;

use App\SheetStock;
use Illuminate\Http\Request;
use Auth;

class SheetStockController extends Controller
{

    public function report(){
        $currentUser = Auth::user();
        $sheet_stock_exist = SheetStock::where('company_id', $currentUser->company_id);
        if($sheet_stock_exist->count()  < 1){
            return \redirect('sheet_productions')->with('error_message', 'Please add sheet production!');
        }
        $sheet_stocks = $sheet_stock_exist->paginate(25);
        return view('admin.reports.sheet_stock', compact('sheet_stocks'));
    }
}
