<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use Auth;

class ProductStock extends Model
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

    public function product(){
        return $this->belongsTo('App\Product');
    }

    public function scopeProductStocks($q){
        $currentUser = Auth::user();
        return $q->where('company_id', $currentUser->company_id);
    }


    public function temporary_direct_productions(){
        return $this->hasMany('App\TemporaryDirectProduction');
    }
}
