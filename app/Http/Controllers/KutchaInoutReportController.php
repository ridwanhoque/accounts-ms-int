<?php

namespace App\Http\Controllers;

use App\FmKutcha;
use App\KutchaWastage;
use Illuminate\Http\Request;
use App\Sheet;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KutchaInoutReportController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $fm_kutchas = FmKutcha::where('company_id', $currentUser->company_id)->pluck('name', 'id');

        $filter['fm_kutcha_id'] = $request->filled('fm_kutcha_id') ? $request->get('fm_kutcha_id'):'';
        $filter['from_date'] = $request->filled('from_date') ? $request->get('from_date'):'';
        $filter['to_date'] = $request->filled('to_date') ? Carbon::parse($request->get('to_date'))->addDays('1'):'';
        
        $report = KutchaWastage::with([
            'fm_kutcha.fm_kutcha_sheet_sum' => function ($qr) use($filter){
                 if($filter['fm_kutcha_id'] != ''){
                    $qr->where('fm_kutcha_id', $filter['fm_kutcha_id']);
                }

                if($filter['from_date'] != ''){
                    $qr->where('created_at', '>=', $filter['from_date']);
                }

                if($filter['to_date'] != ''){
                    $qr->where('created_at', '<=', $filter['to_date']);
                }
            },
        
        'fm_kutcha.fm_kutcha_opening', 
            'fm_kutcha.fm_kutcha_day_opening' => function($qry) use($filter) {
                if($filter['fm_kutcha_id'] != ''){
                    $qry->where('fm_kutcha_id', $filter['fm_kutcha_id']);
                }

                if($filter['from_date'] != ''){
                    $qry->where('created_at', '<=', $filter['from_date']);
                } 
            }]);
                    if($filter['fm_kutcha_id'] != ''){
                        $report->where('fm_kutcha_id', $filter['fm_kutcha_id']);
                    }
    
                    if($filter['from_date'] != ''){
                        $report->where('created_at', '>=', $filter['from_date']);
                    }
    
                    if($filter['to_date'] != ''){
                        $report->where('created_at', '<=', $filter['to_date']);
                    }
            $report->selectRaw('fm_kutcha_id, sum(qty_kg) as fm_kutcha_out_sum')
            ->groupBy('fm_kutcha_id');
        

            // ,
            // 'fm_kutcha.fm_kutcha_out_sum' => function($query) use($filter) {
            //     if($filter['fm_kutcha_id'] != ''){
            //         $query->where('fm_kutcha_id', $filter['fm_kutcha_id']);
            //     }

            //     if($filter['from_date'] != ''){
            //         $query->where('created_at', '>=', $filter['from_date']);
            //     }

            //     if($filter['to_date'] != ''){
            //         $query->where('created_at', '<=', $filter['to_date']);
            //     }
            // }

        // dd($report->get());
        $kutcha_stocks = $report->paginate(25);

        return view('admin.reports.kutcha_in_out_stocks', compact('kutcha_stocks', 'fm_kutchas'));
    }
}
