<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use Auth;

class DailyProductionDetailStock extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();

        if(!App::runningInConsole()){
            static::creating(function($dailyproductiondetails_stock){
                $currentUser = Auth::user();

                $dailyproductiondetails_stock->fill([
                    'company_id' => $currentUser->company_id,
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id
                ]);
            });

            
            static::updating(function($dailyproductiondetails_stock){
                $currentUser = Auth::user();

                $dailyproductiondetails_stock->fill([
                    'company_id' => $currentUser->company_id,
                    'updated_by' => $currentUser->id
                ]);

            });
        }
    }
}
