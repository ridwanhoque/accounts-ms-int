<?php

namespace App\Http\Controllers;

use App\OpeningAsset;
use App\ChartOfAccount;
use Illuminate\Http\Request;
use Auth;
use Message;

class OpeningAssetController extends Controller
{

    public function index(){
        $currentUser = Auth::user();
        $chart_of_accounts = ChartOfAccount::where('type', 'asset')->charts();
        return view('admin.accounting.opening_assets.opening_assets', compact('chart_of_accounts'));
    }

    public function store(Request $request){
        // dd($request->all());
        foreach($request->chart_of_account_ids as $key => $chart_of_account_id){
            $opening_amount = $request->opening_amount[$key];
            $years = $request->years[$key];

            if($opening_amount > 0){
                $opening_asset_exists = OpeningAsset::where('chart_of_account_id', $chart_of_account_id);

                if($opening_asset_exists->count() <= 0){
                    OpeningAsset::create([
                        'chart_of_account_id' => $chart_of_account_id,
                        'opening_amount' => $opening_amount,
                        'years' => $years
                    ]);
                }
            }
        }

        return redirect('opening_assets')->with(['message' => Message::created('opening_asset_stock')]);
    }

}
