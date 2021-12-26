<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ProductDelivery;
use Formatter;
use App;
use Auth;

class ProductDelivery extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();

        if(!App::runningInConsole()){
            static::creating(function($product_delivery){
                $currentUser = Auth::user();
                $max_product_delivery_id = ProductDelivery::max('id') + 1;

                $product_delivery->fill([
                    'company_id' => $currentUser->company_id,
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id,
                    'product_delivery_reference' => Formatter::code('del', $max_product_delivery_id)
                ]);
            });

            static::updating(function($product_delivery){
                $currentUser = Auth::user();

                $product_delivery->fill([
                    'company_id' => $currentUser->company_id,
                    'updated_by' => $currentUser->id
                ]);
            });
        }
    }

    public function status(){
        return $this->belongsTo('App\Status');
    }

    public function product_delivery_details(){
        return $this->hasMany('App\ProductDeliveryDetails');
    }

    public function party(){
        return $this->belongsTo('App\Party');
    }

    
}
