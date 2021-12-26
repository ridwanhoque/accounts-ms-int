<?php

namespace App\Http\Controllers;

use App\ProductBranchStock;
use Illuminate\Http\Request;

class ProductBranchStockController extends Controller
{
    public function report(){
        $product_branch_stocks = ProductBranchStock::get();
        return view('admin.reports.product_branch_stock', compact('product_branch_stocks'));
    }
}
