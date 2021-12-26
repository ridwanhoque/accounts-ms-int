<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App;

class Color extends Model
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

    public function scopeColors($q){
        $currentUser = Auth::user();
        return $q->where('company_id', $currentUser->company_id)->get();
    }

    public function sheet_production_details(){
        return $this->hasMany('App\ProductionDetails');
    }

    public function sheetproductiondetails_colors(){
        return $this->hasMany('App\SheetproductiondetailsColor');
    }

    public function sheet_stocks(){
        return $this->hasMany('App\SheetStock');
    }

    public function sheet_size_colors(){
        return $this->hasMany('App\SheetSizeColor');
    }

    public function products(){
        return $this->hasMany('App\Product');
    }

    public function temporary_sheet_productions(){
        return $this->hasMany('App\TemporarySheetProduction');
    }
    
}
