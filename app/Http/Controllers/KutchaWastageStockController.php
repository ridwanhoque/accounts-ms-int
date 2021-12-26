<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\KutchaWastageStock;
use Auth;

class KutchaWastageStockController extends Controller
{
    public function report(){
        $currentUser = Auth::user();
        $kutcha_wastage_stocks = KutchaWastageStock::where('company_id', $currentUser->company_id)
            ->paginate(25);
        return view('admin.reports.kutcha_wastage_stocks', compact([
            'kutcha_wastage_stocks'
        ]));
    }

}
