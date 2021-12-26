<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use Auth;

class TemporarySheetProductionDetails extends Model
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

    public function temporary_sheet_production(){
        return $this->belongsTo('App\TemporarySheetProduction');
    }
    
    public function sub_raw_material(){
        return $this->belongsTo('App\SubRawMaterial');
    }

    public function batch(){
        return $this->belongsTo('App\Batch');
    }

    public function fm_kutcha(){
        return $this->belongsTo('App\FmKutcha');
    }

    public function color(){
        return $this->belongsTo('App\Color');
    }

    public function sheet_size(){
        return $this->belongsTo('App\SheetSize');
    }

    
}
