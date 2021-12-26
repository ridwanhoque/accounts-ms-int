<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App;

class Batch extends Model
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

    public function sheet_productions(){
        return $this->hasMany('App\SheetProduction');
    }

    public function scopeBatches($q){
        $currentUser = Auth::user();
        return $q->where('company_id', $currentUser->company_id)->get();
    }

    public function purchases(){
        return $this->hasMany('App\Purchase');
    }

    public function raw_material_batch_stocks(){
        return $this->hasMany('App\RawMaterialBatchStock');
    }

    public function sheets(){
        return $this->hasMany('App\Sheet');
    }

    public function direct_production_sheets(){
        return $this->hasMany('App\DirectProductionSheet');
    }

    public function temporary_sheet_productions(){
        return $this->hasMany('App\TemporarySheetProduction');
    }
    
    public function temporary_direct_production_details(){
        return $this->hasMany('App\TemporaryDirectProductionDetails');
    }
}
