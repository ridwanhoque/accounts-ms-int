<?php

namespace App\Traits;

use App\ChartOfAccount;

trait DropdownTrait{
    public function chartOfAccountCombo(){

        $chartsByCode = ChartOfAccount::orderBy('account_code')->get(['head_name', 'tire']);
        $chartCode = '';
        foreach($chartsByCode as $chart){
            $chartCode .= str_repeat('-', $chart->tire).$chart->head_name;
            $chartCode .= nl2br("\n");
        }

        $charts = ChartOfAccount::orderBy('parent_id')->orderBy('id')->select(['id', 'head_name', 'parent_id', 'tire'])
                    ->get();
        $parent_chart_ids = ChartOfAccount::all()->where('parent_id', '>', 0)->pluck('parent_id')->toArray();
        $coa = '';
        // dd($parent_chart_ids);
        foreach($charts as $chart){

            if(in_array($chart->id, $parent_chart_ids)){
                $coa .= str_repeat('- ', $chart->tire).$chart->head_name;                
            }else{

            }
            $coa .= nl2br("\n");


        }

        return $chartCode;

    }
}