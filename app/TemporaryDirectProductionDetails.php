<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TemporaryDirectProductionDetails extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();

        static::creating(function($daily_production){
            $currentUser = Auth::user();

            $daily_production->fill([
                'company_id' => $currentUser->company_id,
                'created_by' => $currentUser->id,
                'updated_by' => $currentUser->id
            ]);
        });

        static::updating(function($daily_production){
            $currentUser = Auth::user();

            $daily_production->fill([
                'company_id' => $currentUser->company_id,
                'updated_by' => $currentUser->id
            ]);
        });
    }

    public function temporary_direct_production(){
        return $this->belongsTo('App\TemporaryDirectProduction');
    }

    public function sub_raw_material(){
        return $this->belongsTo('App\SubRawMaterial');
    }

    public function fm_kutcha(){
        return $this->belongsTo('App\FmKutcha');
    }

    public function product(){
        return $this->belongsTo('App\Product');
    }

    public function machine(){
        return $this->belongsTo('App\Machine');
    }

    public function batch(){
        return $this->belongsTo('App\Batch');
    }

    public function temporary_direct_production_sheetable(){
        return $this->morphTo();
    }

    public function setQtyKgAttribute($value){
        return $attributes['qty_kg'] = $value > 0 ? $value:0;
    }
}
