<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RawMaterialBatchStock extends Model
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


    public function sub_raw_material(){
        return $this->belongsTo('App\SubRawMaterial');
    }

    public function batch(){
        return $this->belongsTo('App\Batch');
    }


}
