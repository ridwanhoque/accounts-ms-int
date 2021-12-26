<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App;

class RawMaterialStock extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        
        parent::boot();

        if(!App::runningInConsole()){
            static::creating(function($raw_material_stock){
            
                $currentUser = Auth::user();
    
                $raw_material_stock->fill([
                    'company_id' => $currentUser->company_id,
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id
                ]);
            });
    
    
            static::updating(function($raw_material_stock){
    
                $currentUser = Auth::user();
    
                $raw_material_stock->fill([
                    'company_id' => $currentUser->company_id,
                    'updated_by' => $currentUser->id
                ]);
    
            });
    
        }

    }


    public function sub_raw_material(){
        return $this->belongsTo('App\SubRawMaterial');
    }


}
