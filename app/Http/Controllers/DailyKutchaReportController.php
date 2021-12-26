<?php

namespace App\Http\Controllers;

use App\FmKutcha;

class DailyKutchaReportController extends Controller
{
    public function report()
    {
        $kutcha_stocks = FmKutcha::with(
            [
                'sum_kutcha_daily_production' => function ($dp) { 
                    
                }, 
                'sum_kutcha_wastages' => function ($kw) { 

                },
                'sum_kutcha_direct_production' => function ($kdp) {

                }
            ]
        )
            ->get();
        // ->paginate(25);

        dd($kutcha_stocks);
        return view('admin.reports.daily_kutcha_stocks', compact('kutcha_stocks'));
    }
}
