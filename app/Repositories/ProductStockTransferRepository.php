<?php

namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\Color;
use App\ProductBranchStock;
use App\ProductStock;
use App\ProductStockTransfer;

class ProductStockTransferRepository implements CrudInterface
{


    public function index()
    { }

    public function create()
    { }

    public function product_stock_transfer_save($request)
    {
        $product_stock_transfer = ProductStockTransfer::create([
            'product_stock_transfer_date' => $request->product_stock_transfer_date,
            'to_branch' => $request->to_branch,
            'total_quantity' => 0,
            'total_pack' => 0,
            'total_weight' => 0
        ]);

        return $product_stock_transfer;
    }

    public function product_stock_transfer_details_save($request, $key, $product_stock_transfer)
    {

        $data = [
            'product_id' => $request->product_ids[$key],
            'quantity' => $request->quantity[$key],
            'pack' => $request->pack[$key],
            'weight' => $request->weight[$key]
        ];

        $product_stock_transfer_details = $product_stock_transfer->product_stock_transfer_details
            ()->create($data);

        return $product_stock_transfer_details;
    }

    public function product_branch_stock_save($request, $key){
        
        $product_branch_stock_exists = ProductBranchStock::where([
            'branch_id' => $request->to_branch,
            'product_id' => $request->product_ids[$key]
        ]);

        if($product_branch_stock_exists->count() > 0){
            $product_branch_stock = $product_branch_stock_exists->first();
        }else{
            $product_branch_stock = new ProductBranchStock;
        }
            $product_branch_stock->branch_id = $request->to_branch;
            $product_branch_stock->product_id = $request->product_ids[$key];
            $product_branch_stock->transferred_quantity += $request->quantity[$key];
            $product_branch_stock->transferred_pack += $request->pack[$key];
            $product_branch_stock->transferred_weight += $request->weight[$key];
            $product_branch_stock->available_quantity += $request->quantity[$key];
            $product_branch_stock->available_pack += $request->pack[$key];
            $product_branch_stock->available_weight += $request->weight[$key];
            $product_branch_stock->save();

            return true;
        
    }

    public function product_stock_save($request, $key){
        $product_stock_exists = ProductStock::where('product_id', $request->product_ids[$key]);

        if($product_stock_exists->count() > 0){
            $product_stock = $product_stock_exists->first();
        }else{
            $product_stock = new ProductStock;
        }

        $product_stock->transferred_quantity += $request->quantity[$key];
        $product_stock->transferred_pack += $request->pack[$key];
        $product_stock->transferred_weight += $request->weight[$key];
        $product_stock->available_quantity -= $request->quantity[$key];
        $product_stock->available_pack -= $request->pack[$key];
        $product_stock->available_weight -= $request->weight[$key];
        $product_stock->save();

        return $product_stock;
    }

    public function store($request)
    {
        //stock transfer
        $product_stock_transfer = $this->product_stock_transfer_save($request);
        
        foreach ($request->product_ids as $key => $product) {
            //stock transfer details
            $this->product_stock_transfer_details_save($request, $key, $product_stock_transfer);

            //branchwise product stock add
            $this->product_branch_stock_save($request, $key);

            //deduct transferred quantity from main stock
            $this->product_stock_save($request, $key);
        }

        return true;
    }

    public function show($color)
    { }

    public function edit($color)
    { }

    public function update($request, $color)
    {
        return Color::findOrFail($color->id)->update($request->all());
    }

    public function destroy($color)
    {
        // return Color::destroy($color->id);
        //product_stock_transfer_save
        //product_stock_transfer_details_save
        //product_stock_transfer_details_save
        //product_stock_save
    }
}
