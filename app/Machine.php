<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App;

class Machine extends Model
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
                    'updated_by' => $currentUser->id
                ]);
            });    
        }

    }

    public function daily_productions(){
        return $this->hasMany('App\DailyProduction');
    }

    public function daily_production_details(){
        return $this->hasMany('App\DailyProductionDetails');
    }

    public function scopeMachines($q){
        $currentUser = Auth::user();
        return $q->where('company_id', $currentUser->company_id)->get();
    }

    public function products(){
        return $this->hasMany('App\Product');
    }
    
    public function direct_production_details(){
        return $this->hasMany('App\DirectProductionDetails');
    }


    public function temporary_direct_productions(){
        return $this->hasMany('App\TemporaryDirectProduction');
    }
}
