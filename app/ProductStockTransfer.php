<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use Illuminate\Support\Facades\Auth;

class ProductStockTransfer extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();

        if(!App::runningInConsole()){
            static::creating(function($product_stock){
                $currentUser = Auth::user();

                $product_stock->fill([
                    'company_id' => $currentUser->company_id,
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id
                ]);
            });

            static::updating(function($product_stock){
                $currentUser = Auth::user();

                $product_stock->fill([
                    'company_id' => $currentUser->company_id,
                    'updated_by' => $currentUser->id
                ]);
            });

        }

    }

    public function company(){
        return $this->belongsTo('App\Company');
    }


    public function transfer_from_branch(){
        return $this->belongsTo('App\Branch', 'from_branch');
    }

    public function transfer_to_branch(){
        return $this->belongsTo('App\Branch', 'to_branch');
    }

    public function product_stock_transfer_details(){
        return $this->hasMany('App\ProductStockTransferDetails');
    }
}