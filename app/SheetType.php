<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use Auth;

class SheetType extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();

        if(!App::runningInConsole()){
            static::creating(function($sheet_type){
                $currentUser = Auth::user();

                $sheet_type->fill([
                    'company_id' => $currentUser->company_id,
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id
                ]);

            });

            static::updating(function($sheet_type){
                $currentUser = Auth::user();

                $sheet_type->fill([
                    'company_id' => $currentUser->company_id,
                    'updated_by' => $currentUser->id
                ]);

            });
        }
    }
    
}
