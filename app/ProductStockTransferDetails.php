<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductStockTransferDetails extends Model
{
    protected $guarded = ['id'];

    public static function boot(){

        parent::boot();

        static::creating(function($model){
            $model->fill([
                'company_id' => auth()->user()->company_id,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id
            ]);
        });


        static::updating(function($model){
            $model->fill([
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id
            ]);
        });
    }

    public function product_stock_transfer(){
        return $this->belongsTo('App\ProductStockTransfer');
    }

    public function product(){
        return $this->belongsTo('App\Product');
    }
}
