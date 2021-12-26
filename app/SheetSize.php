<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use Auth;

class SheetSize extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();

        if(!App::runningInConsole()){
            static::creating(function($sheet_size){
                $currentuser = Auth::user();

                $sheet_size->fill([
                    'company_id' => $currentuser->company_id,
                    'created_by' => $currentuser->id,
                    'updated_by' => $currentuser->id
                ]);

            });


            static::updating(function($sheet_size){
                $currentuser = Auth::user();

                $sheet_size->fill([
                    'company_id' => $currentuser->company_id,
                    'updated_by' => $currentuser->id
                ]);
            });
        }
    }


    public function sheet_production_details(){
        return $this->hasMany('App\SheetProductionDetails');
    }

    public function sheet_stocks(){
        return $this->hasMany('App\SheetStock');
    }

    public function shee_sizes(){
        return $this->hasMany('App\SheetSize');
    }

    public function raw_material(){
        return $this->belongsTo('App\RawMaterial');
    }

    public function scopeSheetSizes($q){
        $currentUser = Auth::user();
        return $q->where('company_id', $currentUser->company_id)->get();
    }
    
    public function temporary_sheet_productions(){
        return $this->hasMany('App\TemporarySheetProduction');
    }

}
