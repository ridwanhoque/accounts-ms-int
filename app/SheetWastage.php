<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App;

class SheetWastage extends Model
{
    public static function boot(){
        parent::boot();

        if(!App::runningInConsole()){
            static::creating(function($sheet_wastage){
                $currentUser = Auth::user();
    
                $sheet_wastage->fill([
                    'company_id' => $currentUser->company_id,
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id
                ]);
            });
    
            static::updating(function($sheet_wastage){
                $currentUser = Auth::user();
    
                $sheet_wastage->fill([
                    'company_id' => $currentUser->company_id,
                    'updated_by' => $currentUser->updated_by
                ]);
            });
    
        }
    }
}
