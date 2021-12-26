<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TemporaryDirectProduction extends Model
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


    public function company(){
        return $this->belongsTo('App\Company');
    }

    public function temporary_direct_production_details(){
        return $this->hasMany('App\TemporaryDirectProductionDetails');
    }
}
