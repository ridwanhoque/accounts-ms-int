<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use Auth;

class TemporarySheetProduction extends Model
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

    public function batch(){
        return $this->belongsTo('App\Batch');
    }

    public function status(){
        return $this->belongsTo('App\Status');
    }
    
    public function setTotalKgAttribute($value){
        return $this->attributes['total_kg'] = $value > 0 ? $value:0; 
    }

    public function setTotalRollAttribute($value){
        return $this->attributes['total_roll'] = $value > 0 ? $value:0;
    }

    public function setPowderAttribute($value){
        return $this->attributes['powder'] = $value > 0 ? $value:0;
    }

    public function setHaddiAttribute($value){
        return $this->attributes['haddi'] = $value > 0 ? $value:0;
    }

    public function temporary_sheet_production_details(){
        return $this->hasMany('App\TemporarySheetProductionDetails');
    }

    public function company(){
        return $this->belongsTo('App\Company');
    }
}
