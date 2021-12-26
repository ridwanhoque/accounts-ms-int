<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\ProductStock;
use Message;

class OpeningProductController extends Controller
{
    public function index(){

        $products = Product::products();

        return view('admin.opening_stocks.products', compact('products'));
    }


    public function store(Request $request){

        // dd(count($request->product_ids));
        foreach($request->product_ids as $key => $product_id){
            
            $quantity = $request->quantity[$key];
            $pack = $request->pack[$key];
            $weight = $request->weight[$key];
            $opening_price = $request->price[$key];
            $opening_price_total = $request->opening_price_total[$key];
            
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
        }


        return redirect('production_settings/opening_products')->with(['message' => Message::created('produc_stock')]);
    }
    
}
