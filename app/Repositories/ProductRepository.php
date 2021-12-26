<?php
namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\Product;
use Illuminate\Support\Facades\Request;
use App\ProductStock;

class ProductRepository implements CrudInterface{

    public function index(){
        return Product::with([
            'color', 'raw_material'
        ])->orderByDesc('id')->products();
    }

    public function create(){

    }

    public function store($request){
        
        Product::create($request->all());
        
    }


    public function opening_product_store($request){

        // dd(count($request->product_ids));
            $product_id = $request->id;
            $quantity = $request->quantity;
            $pack = $request->pack;
            $weight = $request->weight;
            $opening_price = $request->price;
            $opening_price_total = $request->opening_price_total;
            
            if($quantity > 0 || $pack > 0 || $weight > 0 || $opening_price > 0){
                $product_stock_exists = ProductStock::where('product_id', $product_id);

                if($product_stock_exists->count() <= 0){
                    ProductStock::create([
                        'product_id' => $product_id,
                        'opening_quantity' => $quantity,
                        'available_quantity' => $quantity,
                        'opening_pack' => $pack,
                        'available_pack' => $pack,
                        'opening_weight' => $weight,
                        'available_weight' => $weight,
                        'opening_price' => $opening_price,
                        'opening_price_total' => $opening_price_total
                    ]);
                    
                }else{
                    $product_stock = $product_stock_exists->first();
                    if($product_stock->opening_quantity <= 0){
                        $product_stock->opening_quantity += $quantity;
                        $product_stock->available_quantity += $quantity;
                    }
                    
                    if($product_stock->opening_price_total <= 0){
                        $product_stock->opening_price_total += $opening_price_total;
                    }

                    if($product_stock->opening_price <= 0){
                        $product_stock->opening_price += $opening_price;
                    }

                    if($product_stock->opening_pack <= 0){
                        $product_stock->opening_pack += $pack;
                        $product_stock->available_pack += $pack;
                    }

                    if($product_stock->opening_weight <= 0){
                        $product_stock->opening_weight += $weight;
                        $product_stock->available_weight += $weight;
                    }

                    $product_stock->save();

                }
        }


        return redirect('production_settings/opening_products')->with(['message' => 'opening product added success']);
    }

    
    public function show($product){

    }

    public function edit($product){

    }

    public function update($request, $product){

        Product::findOrFail($product->id)->update([
            'raw_material_id' => $request->raw_material_id,
            'machine_id' => $request->machine_id,
            'expected_quantity' => $request->expected_quantity,
            'standard_weight' => $request->standard_weight,
            'name' => $request->name,
            'description' => $request->description,
            'color_id' => $request->color_id
        ]);


        $opening_product_store = $this->opening_product_store($request);

        if($opening_product_store){
            return redirect()->back()->withMessage('Opening product add success!');
        }


    }

    public function destroy($product){
        return $product->delete();
    }

}