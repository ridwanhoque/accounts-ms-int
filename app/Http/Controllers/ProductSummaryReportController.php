<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\FmKutcha;
use DB;

class ProductSummaryReportController extends Controller
{
    public function report(Request $request)
    {
        
        $products = Product::orderBy('name')->products()->all();
        $production = Product::orderByDesc('id')->with([
            'daily_production_sum' => function ($daily_production_sum) use($request) {
                $daily_production_sum->whereHas('daily_production', function($daily_production) use($request){
                    $daily_production->whereBetween('daily_production_date', date_range($request));
                });

            },
            'direct_production_sum' => function ($direct_production_sum) use($request) {
                $direct_production_sum->whereHas('direct_production', function($direct_production) use($request){
                    $direct_production->whereBetween('direct_production_date', date_range($request));
                });
            },
            'stock_transfer_sum' => function ($stock_transfer_sum) use($request) {
                $stock_transfer_sum->whereHas('product_stock_transfer', function($stock_transfer) use($request){
                    $stock_transfer->whereBetween('product_stock_transfer_date', date_range($request));
                });

            },
            'product_delivery_sum' => function ($product_delivery_sum) use($request) {
                $product_delivery_sum->whereHas('product_delivery', function($product_delivery) use($request){
                    $product_delivery->whereBetween('product_delivery_date', date_range($request));
                });

            }]);

        if ($request->product_id > 0) {
            $production->where('id', $request->product_id);
        }

        $production_stock = $production->get();

        // dd($production->get());

        return view('admin.reports.product_summary_stock', compact('products', 'production_stock'));
    }


    public function report_k(Request $request)
    {
        
        $fm_kutchas = FmKutcha::orderBy('name')->fmKutchas()->all();
        $production = FmKutcha::orderByDesc('id')->with([
            'sum_kutcha_daily_production' => function ($sum_kutcha_daily_production) use($request) {
                $sum_kutcha_daily_production->whereBetween(DB::raw('DATE(created_at)'), date_range($request));

            },
            'sum_kutcha_sheet_production' => function ($sum_kutcha_sheet_production) use($request) {
                $sum_kutcha_sheet_production->whereBetween(DB::raw('DATE(created_at)'), date_range($request));

            },
            'sum_kutcha_sheet_production_in' => function ($sum_kutcha_sheet_production_in) use($request) {
                $sum_kutcha_sheet_production_in->whereBetween(DB::raw('DATE(created_at)'), date_range($request));

            },
            'sum_kutcha_direct_production_in' => function ($sum_kutcha_direct_production_in) use($request) {
                $sum_kutcha_direct_production_in->whereBetween(DB::raw('DATE(created_at)'), date_range($request));

            },
            'sum_kutcha_direct_production' => function($sum_kutcha_direct_production) use($request){
                $sum_kutcha_direct_production->whereBetween(DB::raw('DATE(created_at)'), date_range($request));
            }]);

        if ($request->fm_kutcha_id > 0) {
            $production->where('id', $request->fm_kutcha_id);
        }

        $fm_kutcha_stocks = $production->get();

        // dd($production->get());

        return view('admin.reports.summary.products_summary_reports', compact('fm_kutchas', 'fm_kutcha_stocks'));
    }
}
