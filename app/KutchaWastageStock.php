<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use Auth;

class KutchaWastageStock extends Model
{
    
    protected $guarded = ['id'];
	
	public static function boot(){
        parent::boot();

        if(!App::runningInConsole()){
            static::creating(function($kutcha_wastage_stock){
                
                $currentUser = Auth::user();
                
                $kutcha_wastage_stock->fill([
                    'company_id' => $currentUser->company_id,
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id,
                ]);
            });

            static::updating(function($kutcha_wastage_stock){

                $currentUser = Auth::user();

                $kutcha_wastage_stock->fill([
                    'company_id' => $currentUser->company_id,
                    'updated_by' => $currentUser->id,
                ]);

            });
        }
    }

    public function fm_kutcha(){
        return $this->belongsTo('App\FmKutcha');
    }

    public function setKutchaQtyKgsAttribute($value){
        return $this->attributes['kutcha_qty_kgs'] = $value > 0 ? $value : 0; 
    }
}
