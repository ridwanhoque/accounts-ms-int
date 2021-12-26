<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FormingWastageStock;

class FormingWastageStockController extends Controller
{
    public function report(){
        $forming_wastage_stocks = FormingWastageStock::paginate(25);
        return \view('admin.reports.forming_wastage_stocks', compact('forming_wastage_stocks'));
    }
}
