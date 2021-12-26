<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use Auth;

class PurchaseReceive extends Model
{
    protected $guarded = ['id'];

    public static function boot()
    {
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

    public function purchase(){
        return $this->belongsTo('App\Purchase');
    }

    public function purchase_receive_details(){
        return $this->hasMany('App\PurchaseReceiveDetails');
    }

    public function company(){
        return $this->belongsTo('App\Company');
    }

    public function chalan_receive_quantity(){
        return $this->hasOne('App\PurchaseReceiveDetails')->selectRaw('sum(quantity) as sum_quantity, purchase_receive_id')->groupBy('purchase_receive_id');
    }
}
