<?php

namespace App;

use App;
use Auth;
use Illuminate\Database\Eloquent\Model;

class ProductDeliveryDetails extends Model
{
    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();
        if (!App::runningInConsole()) {
            static::creating(function($product_delivery) {
                $currentUser = Auth::user();

                $product_delivery->fill([
                    'company_id' => $currentUser->company_id,
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id,
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

    public function product_delivery(){
        return $this->belongsTo('App\ProductDelivery');
    }

    public function product(){
        return $this->belongsTo('App\Product');
    }

    public function daily_production_detail(){
        return $this->belongsTo('App\DailyProductionDetail');
    }

    public function sale(){
        return $this->belongsTo('App\Sale');
    }

}
