<?php

namespace App;

use App;
use Illuminate\Database\Eloquent\Model;

class SheetMaterialStock extends Model
{
    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();

        if (!App::runningInConsole()) {

            static::creating(function($sheet_material_stock) {
                $currentUser = Auth::user();

                $sheet_material_stock->fill([
                    'company_id' => $currentUser->company_id,
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id
                ]);
            });

            static::updating(function($sheet_material_stock) {
                $currentUser = Auth::user();

                $sheet_material_stock->fill([
                    'company_id' => $currentUser->company_id,
                    'updated_by' => $currentUser->id
                ]);
                
            });
        }

    }

    

}
