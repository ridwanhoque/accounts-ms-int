<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use Auth;

class SheetproductiondetailsColor extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();

        if(!App::runningInConsole()){
            static::creating(function($spdetails_color){
                $currentUser = Auth::user();

                $spdetails_color->fill([
                    'company_id' => $currentUser->company_id,
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id
                ]);
            });

            static::updating(function($spdetails_color){
                $currentUser = Auth::user();

                $spdetails_color->fill([
                    'company_id' => $currentUser->company_id,
                    'updated_by' => $currentUser->id
                ]);
            });

            
        }
    }


    public function sheet_production_details(){
        return $this->belongsTo('App\SheetProductionDetails');
    }

    public function color(){
        return $this->belongsTo('App\Color');
    }

}
