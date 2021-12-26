<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use App\Purchase;

class PurchaseDueController extends Controller
{
    public function report(){
        $purchase_dues = Purchase::whereColumn('total_paid','<','total_payable')->paginate(25);
        return view('admin.accounting.report.purchase_dues', compact('purchase_dues'));
    }
}
