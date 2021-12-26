<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FmKutcha;
use DB;

class KutchaSummaryReportController extends Controller
{
    public function report(Request $request){
		$fm_kutchas = FmKutcha::orderBy('name')->fmKutchas()->all();
        $production = FmKutcha::orderByDesc('id')->with([
            'sum_kutcha_daily_production' => function ($sum_kutcha_daily_production) use($request){
                $sum_kutcha_daily_production->whereHas('daily_production', function($q) use($request){
					$q->whereBetween('daily_production_date', date_range($request));
				});
            },
            'sum_kutcha_sheet_production' => function ($sum_kutcha_sheet_production) use($request) {
                $sum_kutcha_sheet_production->whereHas('sheet_production', function($q) use($request){
                    $q->whereBetween(DB::raw('DATE(sheet_production_date)'), date_range($request));
                });
            },
            'sum_kutcha_sheet_production_in' => function ($sum_kutcha_sheet_production_in) use($request) {
                $sum_kutcha_sheet_production_in->whereHas('sheet_production', function($q) use($request){
                    $q->whereBetween(DB::raw('DATE(sheet_production_date)'), date_range($request));
                });
            },
            'sum_kutcha_direct_production_in' => function ($sum_kutcha_direct_production_in) use($request) {
                $sum_kutcha_direct_production_in->whereHas('direct_production', function($q) use($request){
                    $q->whereBetween(DB::raw('DATE(direct_production_date)'), date_range($request));
                });

            },
            'sum_kutcha_direct_production' => function($sum_kutcha_direct_production) use($request){
                $sum_kutcha_direct_production->whereHas('direct_production', function($q) use($request){
					$q->whereBetween('direct_production_date', date_range($request));
				});
            }]);

        if ($request->fm_kutcha_id > 0) {
            $production->where('id', $request->fm_kutcha_id);
        }

        $fm_kutcha_stocks = $production->get();

        // dd($production->get());

        return view('admin.reports.summary.kutcha_summary_reports', compact('fm_kutchas', 'fm_kutcha_stocks'));
	}
}















