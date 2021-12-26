<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class DirectProductionDetails extends Model
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
                'updated_by' => $currentUser->id
            ]);
        });
    }


    public function direct_production(){
        return $this->belongsTo('App\DirectProduction');
    }

    public function product(){
        return $this->belongsTo('App\Product');
    }

    public function fm_kutcha(){
        return $this->belongsTo('App\FmKutcha');
    }

    public function machine(){
        return $this->belongsTo('App\Machine');
    }

    public function setNetWeightAttribute($value){
        return $this->attributes['net_weight'] =  ($value != null) ? $value:0;
    }

    
}
