<?php
namespace App\Repositories;

use App\Asset;
use App\AssetDetails;
use App\Interfaces\CrudInterface;
use App\Transection;

class AssetRepository implements CrudInterface{

    public function index()
    {
        
    }

    public function create(){}

    public function store($request){
        $asset = $this->save_assets($request);

        foreach($request->chart_of_account_id as $key => $assetchart_id){
            $asset_details = $this->save_asset_details($request, $key, $asset->id);
       
            $this->save_transactions($request, $key, $asset_details->id);

        }

        return true;
    }

    public function save_assets($request){
        $asset = Asset::create([
            'party_id' => $request->party_id,
            'asset_date' => $request->asset_date,
            'asset_reference' => $request->asset_reference,
            'total_amount' => $request->total_amount
        ]);

        return $asset;
    }

    public function save_asset_details($request, $key, $asset_id){
        $data = [
            'asset_id' => $asset_id,
            'chart_of_account_id' => $request->chart_of_account_id[$key],
            'account_information_id' => $request->account_information_id[$key],
            'payment_method_id' => $request->payment_method_id[$key],
            'years' => $request->years[$key],
            'amount' => $request->amount[$key]
        ];

        $asset_details = AssetDetails::create($data);

        return $asset_details;
    }

    public function edit($model){}

    public function update($request, $model)
    {
        
    }

    public function show($model){ }

    public function destroy($model)
    {
        $model->asset_details()->delete();
        $model->delete();

        return true;
    }
    

    public function save_transactions($request, $key, $asset_details_id){
       
       $transaction = Transection::create([
            'account_information_id' => $request->account_information_id[$key],
            'amount' => $request->amount[$key]*(-1),
            'transactionable_type' => 'App\AssetDetails',
            'transactionable_id' => $asset_details_id
       ]);

       return $transaction;
    }
}