<?php

namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\PurchaseDetails;
use App\PurchaseReceive;
use App\PurchaseReceiveDetails;
use App\RawMaterialStock;
use App\RawMaterialBatchStock;

class PurchaseReceiveRepository implements CrudInterface
{
    public function index()
    { }

    public function create()
    { }

    public function store($request)
    {

        $error_message = '';
        foreach ($request->quantity as $key => $qty) {
            $details = [
                'purchase_id' => $request->purchase_id,
                'sub_raw_material_id' => $request->sub_raw_material_id[$key],
                'quantity' => $qty,
            ];

            //check purchase received quantity not more than purchase order quantity
            $receive_quantity_not_exceeds = $this->check_purchase_quantity($details);

            if ($receive_quantity_not_exceeds == false) {
                $error_message = 'Received quantity can not be more than order!';
            }
        }
        if ($error_message != '') {
            return $error_message;
        }

        //store into purchase received table
        $purchase_receive = $this->save_purchase_receive($request);

        foreach ($request->quantity as $key => $qty) {
            $details = [
                'purchase_id' => $request->purchase_id,
                'purchase_receive_id' => $purchase_receive->id,
                'sub_raw_material_id' => $request->sub_raw_material_id[$key],
                'quantity' => $qty,
                'quantity_bag' => $request->quantity_bag[$key],
                'batch_id' => $purchase_receive->purchase->batch_id
            ];

            //check purchase received quantity not more than purchase order quantity
            $receive_quantity_not_exceeds = $this->check_purchase_quantity($details);

            // if ($receive_quantity_not_exceeds) {
            //store details into purchase related info
            $this->save_purchase_receive_details($details);

            //store into raw material stock table
            $this->save_sub_raw_material_stock($details);

            //store into raw material batch stock table
            $this->save_sub_raw_material_batch_stock($details);

            // } else {
            //     $error += 1;
            //     return \redirect('purchase_receives')->with('error_message', 'Received quantity can not be more than order!');
            // }

        }
        if ($error_message == '') {
            return 'success';
        }
    }

    public function max_purchase_receive_id()
    {
        $max_purchase_id = PurchaseReceive::max('id') + 1;

        return $max_purchase_id;
    }

    public function check_purchase_quantity($received_details)
    {
        $order_quantity = PurchaseDetails::where([
            'purchase_id' => $received_details['purchase_id'],
            'sub_raw_material_id' => $received_details['sub_raw_material_id'],
        ])->first()->quantity;

        $total_received = $received_details['quantity'] + $this->received_by_purchase_id($received_details);
        if ($total_received > $order_quantity) {
            return false;
        } else {
            return true;
        }
    }

    public function received_by_purchase_id($received_details)
    {
        $sum_quantity = PurchaseReceiveDetails::join('purchase_receives', 'purchase_receive_details.purchase_receive_id', '=', 'purchase_receives.id')
            ->where([
                'purchase_receives.purchase_id' => $received_details['purchase_id'],
                'purchase_receive_details.sub_raw_material_id' => $received_details['sub_raw_material_id'],
            ])
            ->selectRaw('sum(quantity) as sum')
            ->pluck('sum');
        return $sum_quantity->first();
    }

    public function show($purchase_receive)
    { }

    public function edit($purchase_receive)
    { }

    public function update($request, $purchase_receive)
    {
        $error_message = '';
        foreach ($request->quantity as $key => $qty) {
            $details = [
                'purchase_id' => $request->purchase_id,
                'sub_raw_material_id' => $request->sub_raw_material_id[$key],
                'quantity' => $qty,
            ];

            //check purchase received quantity not more than purchase order quantity
            $receive_quantity_not_exceeds = $this->check_purchase_quantity($details);

            if ($receive_quantity_not_exceeds == false) {
                $error_message = 'Received quantity can not be more than order!';
            }
        }
        if ($error_message != '') {
            return $error_message;
        }

        //update purchase receive data
        $purchase_receive = $this->save_purchase_receive($request, $request->id);

        foreach ($request->quantity as $key => $qty) {
            $details = [
                'purchase_receive_id' => $purchase_receive->id,
                'sub_raw_material_id' => $request->sub_raw_material_id[$key],
                'quantity' => $qty,
                'quantity_bag' => $request->quantity_bag[$key]
            ];

            //update data related to purchase receive
            $this->save_purchase_receive_details($details, $request->purchase_receive_details_id[$key]);

            //update data into raw material stock
            $this->save_sub_raw_material_stock($details);
        }

        if ($error_message == '') {
            return 'success';
        }
    }

    public function destroy($purchase_receive)
    {
        //deduct amount from raw material stock
        $this->delete_sub_raw_material_stock($purchase_receive);

        //deduct amount from raw material stock
        $this->delete_sub_raw_material_batch_stock($purchase_receive);

        //delete related data
        $this->delete_purchase_receive_details($purchase_receive);


        return $purchase_receive->delete();
    }


    public function save_purchase_receive($request, $id = null)
    {

        if ($id != null) {
            $purchase_receive = PurchaseReceive::findOrFail($id);
        } else {
            $purchase_receive = new PurchaseReceive;
        }
        $purchase_receive->purchase_id = $request->purchase_id;
        $purchase_receive->purchase_receive_date = $request->purchase_receive_date;
        $purchase_receive->chalan_number = $request->chalan_number;
        $purchase_receive->save();

        return $purchase_receive;
    }

    public function save_purchase_receive_details($details, $id = null)
    {
        if ($id != null) {
            $purchase_receive_details = PurchaseReceiveDetails::findOrFail($id);
        } else {
            $purchase_receive_details = new PurchaseReceiveDetails;
        }

        $purchase_receive_details->purchase_receive_id = $details['purchase_receive_id'];
        $purchase_receive_details->sub_raw_material_id = $details['sub_raw_material_id'];
        $purchase_receive_details->quantity = $details['quantity'];
        $purchase_receive_details->quantity_bag = $details['quantity_bag'];
        $purchase_receive_details->save();

        return $purchase_receive_details;
    }

    public function save_sub_raw_material_stock($details)
    {
        //if raw material is already added to stock table then update else store
        $sub_raw_material_stock_all = RawMaterialStock::where('sub_raw_material_id', $details['sub_raw_material_id']);
        if ($sub_raw_material_stock_all->count() > 0) {
            $sub_raw_material_stock = $sub_raw_material_stock_all->first();
        } else {
            $sub_raw_material_stock = new RawMaterialStock;
            $sub_raw_material_stock->sub_raw_material_id = $details['sub_raw_material_id'];
        }
        $sub_raw_material_stock->available_quantity += $details['quantity'];
        $sub_raw_material_stock->purchased_quantity += $details['quantity'];
        $sub_raw_material_stock->purchased_bags += $details['quantity_bag'];
        $sub_raw_material_stock->save();

        return $sub_raw_material_stock;
    }

    public function save_sub_raw_material_batch_stock($details)
    {
        //if raw material is already added to stock table then update else store
        $sub_raw_material_stock_all = RawMaterialBatchStock::where([
            'sub_raw_material_id' => $details['sub_raw_material_id'],
            'batch_id' => $details['batch_id']
            ]);
        if ($sub_raw_material_stock_all->count() > 0) {
            $sub_raw_material_stock = $sub_raw_material_stock_all->first();
        } else {
            $sub_raw_material_stock = new RawMaterialBatchStock;
            $sub_raw_material_stock->sub_raw_material_id = $details['sub_raw_material_id'];
            $sub_raw_material_stock->batch_id = $details['batch_id'];
        }
        $sub_raw_material_stock->available_quantity += $details['quantity'];
        $sub_raw_material_stock->purchased_quantity += $details['quantity'];
        $sub_raw_material_stock->purchased_bags += $details['quantity_bag'];
        $sub_raw_material_stock->available_bags += $details['quantity_bag'];
        $sub_raw_material_stock->save();

        return $sub_raw_material_stock;

    }


    public function delete_purchase_receive_details($purchase_receive)
    {
        foreach ($purchase_receive->purchase_receive_details as $details) {
            $details->delete();
        }

        return true;
    }

    public function delete_sub_raw_material_stock($purchase_receive)
    {
        foreach ($purchase_receive->purchase_receive_details as $details) {
            $sub_raw_material_stock = RawMaterialStock::where('sub_raw_material_id', $details->sub_raw_material_id)->first();
            $sub_raw_material_stock->available_quantity -= $details->quantity;
            $sub_raw_material_stock->purchased_quantity -= $details->quantity;
            $sub_raw_material_stock->purchased_bags -= $details->quantity_bag;
            $sub_raw_material_stock->available_bags -= $details->quantity_bag;
            $sub_raw_material_stock->save();
        }

        return true;
    }

    public function delete_sub_raw_material_batch_stock($purchase_receive)
    {
        foreach ($purchase_receive->purchase_receive_details as $details) {
            $sub_raw_material_stock = RawMaterialBatchStock::where([
                'sub_raw_material_id' => $details->sub_raw_material_id,
                'batch_id' => $purchase_receive->purchase->batch_id
                ])->first();
            $sub_raw_material_stock->available_quantity -= $details->quantity;
            $sub_raw_material_stock->purchased_quantity -= $details->quantity;
            $sub_raw_material_stock->purchased_bags -= $details->quantity_bag;
            $sub_raw_material_stock->available_bags -= $details->quantity_bag;
            $sub_raw_material_stock->save();
        }

        return true;
    }

}
