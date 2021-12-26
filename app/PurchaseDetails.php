<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class PurchaseDetails extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();

        static::creating(function($model){
            $currentUser = Auth::user();

            $model->fill([
                'company_id' => $currentUser->company_id,
                'created_by' => $currentUser->id,
                'updated_by' => $currentUser->id
            ]);
        });

        static::updating(function($model){
            $currentUser = Auth::user();

            $model->fill([
                'company_id' => $currentUser->company_id,
                'created_by' => $currentUser->id,
                'updated_by' => $currentUser->id
            ]);
        });
    }

    public function purchase(){
        return $this->belongsTo('App\Purchase');
    }

    public function sub_raw_material(){
        return $this->belongsTo('App\SubRawMaterial');
    }

}
