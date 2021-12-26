<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SheetWastageStock;

class SheetWastageStockController extends Controller
{
    public function report(){
        $sheet_wastage_stocks = SheetWastageStock::paginate(25);
        return view('admin.reports.sheet_wastage_stocks', compact('sheet_wastage_stocks'));
    }
}
