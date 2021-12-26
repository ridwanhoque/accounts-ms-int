<?php

namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\SaleQuotation;
use App\SaleQuotationDetail;

class SaleQuotationRepository implements CrudInterface{
    public function index(){

    }

    public function create(){

    }

    public function store($request){
        $sale_quotation = $this->save_sale_quotation($request);

        foreach($request->product_ids as $key => $product_id){
            $sale_quotation_details = $this->save_sale_quotation_details($request, $key, $sale_quotation);
        }
        
        return $sale_quotation;
    }

    public function save_sale_quotation($request, $update_id = null){
        $data = [
            'party_id' => $request->party_id,
            'sale_quotation_date' => $request->sale_quotation_date,
            'sub_total' => $request->sub_total,
            'invoice_discount' => $request->invoice_discount,
            'invoice_vat' => $request->invoice_vat,
            'invoice_tax' => $request->invoice_tax,
            'tax_percent' => $request->tax_percent,
            'total_payable' => $request->total_payable
        ];

        $sale_quotation = $update_id > 0 ? SaleQuotation::find($update_id)->update($data) : SaleQuotation::create($data);
        return $sale_quotation;
    }

    public function save_sale_quotation_details($request, $key, $sale_quotation, $update_id = null){
        $data = [
            'unit_price' => $request->price[$key],
            'quantity' => $request->qty[$key],
            'product_id' => $request->product_ids[$key],
            'product_sub_total' => $request->total[$key]
        ];

        $sale_quotation_details = $update_id > 0 ? SaleQuotationDetail::find($update_id)->update($data):$sale_quotation->sale_quotation_details()->create($data);

        return $sale_quotation_details;
    }

    public function show($sale_quotation){

    }

    public function edit($sale_quotation){

    }

    public function update($request, $saleQuotation){
        $sale_quotation = $this->save_sale_quotation($request, $saleQuotation->id);

        foreach($request->product_ids as $key => $product_id){
            $sale_quotation_details = $this->save_sale_quotation_details($request, $key, $sale_quotation, $request->sale_quotation_details_ids[$key]);
        }

        return $sale_quotation;

    }

    public function destroy($sale_quotation){

        $sale_quotation->sale_quotation_details()->delete();

        $sale_quotation->delete();

        return true;
    }

}