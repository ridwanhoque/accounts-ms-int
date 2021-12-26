<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use Auth;

class PurchaseReceiveDetails extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();

        if (!App::runningInConsole()) {
            static::creating(function ($purchase_receive) {
                $currentUser = Auth::user();

                $purchase_receive->fill([
                    'company_id' => $currentUser->company_id,
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id
                ]);

            });

            static::updating(function($purchase_receive){
                $currentUser = Auth::user();

                $purchase_receive->fill([
                    'company_id' => $currentUser->company_id,
                    'updated_by' => $currentUser->id
                ]);
            });
        }

    }

    public function purchase_receive(){
        return $this->belongsTo('App\PurchaseReceive');
    }

    public function sub_raw_material(){
        return $this->belongsTo('App\SubRawMaterial');
    }

    public static function sum_received_bags(){
        return selectRaw('sum(quantity_bag) as bags, sub_raw_material_id')->groupBy('sub_raw_material_id');
    }

    public function sum_used_quantity(){
    
    }

    public function purchased_bags(){}

    public function sum_quantity(){
        return $this->groupBy('sub_raw_material_id')->sum('quantity');
    }
}
