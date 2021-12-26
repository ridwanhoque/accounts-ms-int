<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App;

class OpeningAsset extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();

        if(!App::runningInConsole()){
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
                    'updated_by' => $currentUser->updated_by
                ]);
            });

        }

        
    }


    public function chart_of_account(){
        return $this->belongsTo('App\ChartOfAccount');
    }
}
