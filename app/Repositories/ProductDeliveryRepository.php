<?php
namespace App\Repositories;

use App\DailyProductionDetailStock;
use App\Interfaces\CrudInterface;
use App\ProductDelivery;
use App\ProductDeliveryDetails;
use App\ProductStock;
use App\SaleDetails;
use App\ProductBranchStock;

class ProductDeliveryRepository implements CrudInterface
{

    public function index()
    {

    }

    public function create()
    {

    }

    public function store($request)
    {

        $error_message = '';

        foreach ($request->product_ids as $key => $product_id) {

            $quantity = $request->quantity[$key];
            $pack = $request->pack[$key];

            $details = [
                'quantity' => $quantity,
                'pack' => $pack,
                'product_id' => $request->product_ids[$key],
                'sale_id' => $request->sale_ids[$key],
            ];
            //check delivery quantity less than finish product quantity
            // $check_finish_product_available = $this->check_finish_product_available($details);
            // if($check_finish_product_available != 'success'){
            //     $error_message = $check_finish_product_available;
            // }

            //check delivery quantity less than sale invoice quantity
            $check_sale_invoice_not_exceeds = $this->check_sale_invoice_not_exceeds($details);
            if($check_sale_invoice_not_exceeds != 'success'){
                $error_message = $check_sale_invoice_not_exceeds;
            }
            

        }

        if($error_message != ''){
            return $error_message;
        }

        //store into product deliveries table
        $product_delivery = $this->save_product_deliveries($request);

        foreach ($request->product_ids as $key => $product_id) {
            //store into product delivery details
            $this->save_product_delivery_details($request, $product_delivery->id, $key);

            //if status is complete then store into product stock
            if ($request->status_id == 1) {
                //store into product stock
                $this->save_product_stocks($request->product_ids[$key], $request->quantity[$key], $request->pack[$key], $request->weight[$key], null, $request->branch_ids[$key]);

                //store data into daily production details stock
                // $this->save_daily_production_details_stocks($product_id, $request->product_ids[$key], $request->quantity[$key]);

                //store into product_branch_stock


            }
        }

        return 'success';

    }

    public function show($product_delivery)
    {

    }

    public function edit($product_delivery)
    {

    }

    public function update($request, $product_delivery)
    {
        $error_message = '';

        foreach ($request->product_ids as $key => $product_id) {

            $quantity = $request->quantity[$key];

            $details = [
                'quantity' => $quantity,
                'product_id' => $request->product_ids[$key],
                'sale_id' => $request->sale_ids[$key]
            ];
            //check delivery quantity less than finish product quantity
            // $check_finish_product_available = $this->check_finish_product_available($details);
            // if($check_finish_product_available != 'success'){
            //     $error_message = $check_finish_product_available;
            // }

            //check delivery quantity less than sale invoice quantity
            $check_sale_invoice_not_exceeds = $this->check_sale_invoice_not_exceeds($details);
            if($check_sale_invoice_not_exceeds != 'success'){
                $error_message = $check_sale_invoice_not_exceeds;
            }
            
        }

        if($error_message != ''){
            return $error_message;
        }
        //store into product deliveries table
        $product_delivery = $this->save_product_deliveries($request, $product_delivery->id);

        foreach ($request->product_ids as $key => $product_id) {
            //store into product delivery details
            $this->save_product_delivery_details($request, $product_delivery->id, $key, $request->product_delivery_details_id);

            //if status is complete then store into product stock
            if ($request->status_id == 1) {
                //store into product stock
                $this->save_product_stocks($request->product_ids[$key], $request->quantity[$key], $request->pack[$key], $request->weight[$key], null, $request->branch_ids[$key]);

                //store data into daily production details stock
                // $this->save_daily_production_details_stocks($product_id, $request->product_ids[$key], $request->quantity[$key]);

            }
        }

        return 'success';
    }

    public function destroy($product_delivery)
    {
        foreach($product_delivery->product_delivery_details as $details){
            $this->save_product_stocks($details->product_id, $details->quantity, $details->pack, $details->weight, 'delete', $details->branch_id);
            $details->delete();
        }
        $product_delivery->delete();

        return true;
    }

    public function check_finish_product_available($details)
    {

        $daily_production_details = DailyProductionDetailStock::where([
            'daily_production_details_id' => $details['daily_production_details_id'],
            'product_id' => $details['product_id'],
        ]);
        if ($daily_production_details->count() > 0) {
            $available_quantity = $daily_production_details->first()->available_quantity;
            if ($details['quantity'] <= $available_quantity) {
                return 'success';
            } else {
                return 'Product stock for this lot unavailable!';
            }
        } else {
            return 'Choose another lot for this product!';
        }
    }

    public function check_sale_invoice_not_exceeds($details)
    {
        $sale_details = SaleDetails::where([
            'sale_id' => $details['sale_id'],
            'product_id' => $details['product_id'],
        ]);

        if ($sale_details->count() > 0) {
            if ($details['quantity'] <= $sale_details->first()->quantity) {
                return 'success';
            } else {
                return 'Sale invoice quantity exceeds!';
            }
        } else {
            return 'Choose another invoice for this product!';
        }

    }


    //store into product deliveries table
    public function save_product_deliveries($request, $id = null)
    {
        if($id != null){
            $product_delivery = ProductDelivery::findOrFail($id);
        }else{
            $product_delivery = new ProductDelivery;
        }
        $product_delivery->status_id = $request->status_id;
        $product_delivery->product_delivery_date = $request->product_delivery_date;
        $product_delivery->driver_name = $request->driver_name;
        $product_delivery->driver_phone = $request->driver_phone;
        $product_delivery->reference_name = $request->reference_name;
        $product_delivery->product_delivery_chalan = $request->product_delivery_chalan;
        $product_delivery->save();

        return $product_delivery;
    }

    //store into product delivery details table
    public function save_product_delivery_details($request, $product_delivery_id, $key, $id = null)
    {
        if($id != null){
            $productDeliveryDetails = ProductDeliveryDetails::findOrFail($id);
        }else{
            $productDeliveryDetails = new ProductDeliveryDetails;
        }
        $productDeliveryDetails->product_delivery_id = $product_delivery_id;
        $productDeliveryDetails->product_id = $request->product_ids[$key];
        $productDeliveryDetails->sale_id = $request->sale_ids[$key];
        $productDeliveryDetails->quantity = $request->quantity[$key];
        $productDeliveryDetails->pack = $request->pack[$key];
        $productDeliveryDetails->weight = $request->weight[$key];
        $productDeliveryDetails->branch_id = $request->branch_ids[$key] > 0 ? $request->branch_ids[$key]:NULL;
        $productDeliveryDetails->save();

        return $productDeliveryDetails;

    }

    //store into product stock table
    public function save_product_stocks($product_id, $quantity, $pack, $weight, $delete = null, $branch_id = null)
    {
        if($branch_id > 0){
            $product_stock = ProductBranchStock::where([
                'product_id' => $product_id,
                'branch_id' => $branch_id
            ])->first();
            if($delete != null){
                $product_stock->sold_quantity -= $quantity;
                $product_stock->available_quantity += $quantity;
                $product_stock->sold_pack -= $pack;
                $product_stock->available_pack += $pack;
                $product_stock->sold_weight -= $weight;
                $product_stock->available_weight += $weight;
            }else{
                $product_stock->sold_quantity += $quantity;
                $product_stock->available_quantity -= $quantity;
                $product_stock->sold_pack += $pack;
                $product_stock->available_pack -= $pack;
                $product_stock->sold_weight += $weight;
                $product_stock->available_weight -= $weight;
            }
        }else{
            $product_stock = ProductStock::where('product_id', $product_id)->first();
            if($delete != null){
                $product_stock->sold_quantity -= $quantity;
                $product_stock->available_quantity += $quantity;
                $product_stock->sold_pack -= $pack;
                $product_stock->available_pack += $pack;
                $product_stock->sold_weight -= $weight;
                $product_stock->available_weight += $weight;
            }else{
                $product_stock->sold_quantity += $quantity;
                $product_stock->available_quantity -= $quantity;
                $product_stock->sold_pack += $pack;
                $product_stock->available_pack -= $pack;
                $product_stock->sold_weight += $weight;
                $product_stock->available_weight -= $weight;
            }
        }
 
        $product_stock->save();

        return true;
    }

    public function save_daily_production_details_stocks($daily_production_details_id, $product_id, $quantity, $delete = null)
    {
        $daily_production_details_stock = DailyProductionDetailStock::where([
            'daily_production_details_id' => $daily_production_details_id,
            'product_id' => $product_id
            ])->firstOrFail();
            if($delete != null){
                $daily_production_details_stock->available_quantity += $quantity;
                $daily_production_details_stock->sold_quantity -= $quantity;        
            }else{
                $daily_production_details_stock->available_quantity -= $quantity;
                $daily_production_details_stock->sold_quantity += $quantity;        
            }
        $daily_production_details_stock->save();

        return true;
    }

}
