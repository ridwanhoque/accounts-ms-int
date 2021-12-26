<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App;

class MaterialWastage extends Model
{
    public static function boot(){
        parent::boot();

        if(!App::runningInConsole()){
            static::creating(function($material_wastage){
                $currentUser = Auth::user();
                
                $material_wastage->fill([
                    'company_id' => $currentUser->company_id,
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id
                ]);
            });

            static::updating(function($material_wastage){
                $currentUser = Auth::user();

                $material_wastage->fill([
                    'company_id' => $currentUser->company_id,
                    'updated_by' => $currentUser->updated_by
                ]);
            });

        }

        
    }
}
