<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class DirectProductionSheet extends Model
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

    public function direct_production_sheetable(){
        return $this->morphTo();
    }

    public function batch(){
        return $this->belongsTo('App\Batch');
    }
}
