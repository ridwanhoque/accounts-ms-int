<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use Auth;

class FormingWastageStock extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();

        if(!App::runningInConsole()){
            static::creating(function($forming_wastage_stock){
                $currentUser = Auth::user();

                $forming_wastage_stock->fill([
                    'company_id' => $currentUser->company_id,
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id
                ]);
            });

            static::updating(function($forming_wastage_stock){
                $currentUser = Auth::user();

                $forming_wastage_stock->fill([
                    'company_id' => $currentUser->company_id,
                    'updated_by' => $currentUser->id
                ]);
            });

            
        }
    }

    public function product(){
        return $this->belongsTo('App\Product');
    }

    public function sheet_production_details(){
        return $this->belongsTo('App\SheetProductionDetails');
    }
}
