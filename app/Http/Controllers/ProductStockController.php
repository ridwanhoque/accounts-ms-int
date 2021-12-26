<?php

namespace App\Http\Controllers;

use App\ProductStock;
use App\SaleDetails;
use Illuminate\Http\Request;
use App\ProductBranchStock;

class ProductStockController extends Controller
{
    public function report()
    {
        $product_stocks = ProductStock::productStocks()->get();

        return view('admin.reports.product_stock', compact('product_stocks'));
    }

    public function ajax_get_product_stock(Request $request)
    {
        if ($request->ajax()) {
            $product_id = $request->id;
            $product_stocks = ProductStock::where('product_id', $product_id);
            $data['product_stock'] = 0;
            $data['sale_invoice_quantity'] = 0;
            $data['pack_stock'] = 0;
            $data['sale_invoice_pack'] = 0;
            $data['weight_stock'] = 0;
            $data['sale_invoice_weight'] = 0;
            $data['product_branch'] = 0;
            $data['pack_branch'] = 0;
            $data['weight_branch'] = 0;

            if ($product_stocks->count() > 0) {
                $sale_details = SaleDetails::where('product_id', $product_id)
                    ->whereHas('sale.product_delivery_details', function ($q) use ($product_id) {
                        $q->where('product_id', $product_id);
                    });

                if ($sale_details->count() <= 0) {
                    $sale_details = SaleDetails::where('product_id', $product_id);
                    $data['sale_invoice_quantity'] = $sale_details->sum('quantity');
                    $data['sale_invoice_pack'] = $sale_details->sum('pack');
                    $data['sale_invoice_weight'] = $sale_details->sum('weight');
                }
                $product_stock = $product_stocks->first();
                $data['product_stock'] = $product_stock->available_quantity;
                $data['pack_stock'] = $product_stock->available_pack;
                $data['weight_stock'] = $product_stock->available_weight;
            }

            $product_branch_stock_exist = ProductBranchStock::where('product_id', $product_id);
            if ($product_branch_stock_exist->count() > 0) {
                $product_branch_stock = $product_branch_stock_exist->first();
                $data['product_branch'] = $product_branch_stock_exist->sum('available_quantity');
                $data['pack_branch'] = $product_branch_stock_exist->sum('available_pack');
                $data['weight_branch'] = $product_branch_stock_exist->sum('available_weight');
            }
            return response()->json($data);
        }
    }


    public function ajax_get_product_branch_stock(Request $request)
    {
        if ($request->ajax()) {
            $product_id = $request->product_id;
            $branch_id = $request->branch_id;


            $data['product_quantity'] = 0;
            $data['product_pack'] = 0;
            $data['product_weight'] = 0;

            if($branch_id > 0){
                $product_branch_stock_exists = ProductBranchStock::where([
                    'product_id' => $product_id,
                    'branch_id' => $branch_id
                ]);
    
                if($product_branch_stock_exists->count() > 0){
                    $product_branch_stock = $product_branch_stock_exists->first();
                    $data['product_quantity'] = $product_branch_stock->available_quantity;
                    $data['product_pack'] = $product_branch_stock->available_pack;
                    $data['product_weight'] = $product_branch_stock->available_weight;
                }    
            }else{
                $product_stock_exists = ProductStock::where('product_id', $product_id);

                if($product_stock_exists->count() > 0){
                    $product_stock = $product_stock_exists->first();
                    $data['product_quantity'] = $product_stock->available_quantity;
                    $data['product_pack'] = $product_stock->available_pack;
                    $data['product_weight'] = $product_stock->available_weight;
                }
            }

            return response()->json($data);
        }
    }
}
