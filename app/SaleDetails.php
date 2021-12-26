<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use Auth;

class SaleDetails extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();

        if(!App::runningInConsole()){
            static::creating(function($sale_details){
                $currentUser = Auth::user();

                $sale_details->fill([
                    'company_id' => $currentUser->company_id,
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id
                ]);

            });

            static::updating(function($sale_details){
                $currentUser = Auth::user();

                $sale_details->fill([
                    'company_id' => $currentUser->company_id,
                    'updated_by' => $currentUser->id
                ]);

            });
        }

    }

    public function sale(){
        return $this->belongsTo('App\Sale');
    }

    public function product(){
        return $this->belongsTo('App\Product');
    }
}
