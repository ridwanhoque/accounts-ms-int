<?php

namespace App\Http\Controllers;

use App\HaddiPowderStock;
use Illuminate\Http\Request;

class HaddiPowderStockController extends Controller
{
    public function index(Request $request){
        $haddi_powder_stocks = HaddiPowderStock::paginate(25);
        return view('admin.reports/haddi_powder_stocks', compact('haddi_powder_stocks'));
    }
}
