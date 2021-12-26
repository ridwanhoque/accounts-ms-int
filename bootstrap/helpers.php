<?php 
use Illuminate\Support\Facades\Route;



function checkActiveUrl($route){
    return (Route::currentRouteName()==$route);
}

function userId(){
    return auth()->user()->id;
}

function companyId(){
    return auth()->user()->company_id;
}


function get_chart_id($search_string = NULL){
    return $search_string != NULL ? optional(\App\ChartOfAccount::where('slug', $search_string)->first())->id:'';
}

function inventories_chart_id(){
    return get_chart_id('purchase');
}

function sales_chart_id(){
    return get_chart_id('sales');
}

function payable_chart_id(){
    return get_chart_id('accounts-and-other-trade-payable');
}

function receivable_chart_id(){
    return get_chart_id('accounts-receivable-and-other-receivable');
}

function date_range($request){
    $from_date = $request->filled('from_date') ? $request->from_date:'1970-01-01';
    $to_date = $request->filled('to_date') ? $request->to_date:Carbon\Carbon::tomorrow()->toDateString();

    return [$from_date, $to_date];
}