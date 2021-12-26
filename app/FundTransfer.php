<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use Auth;

class FundTransfer extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        
        parent::boot();

        if(!App::runningInConsole()){
            static::creating(function($fund_transfer){
                $currentUser = Auth::user();

                $fund_transfer->fill([
                    'company_id' => $currentUser->company_id,
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id
                ]);
            });


            static::updating(function($fund_transfer){
                $currentUser = Auth::user();

                $fund_transfer->fill([
                    'company_id' => $currentUser->company_id,
                    'updated_by' => $currentUser->id
                ]);
            });
        }    
    }


    public function from_account(){
        return $this->belongsTo('App\AccountInformation', 'from_account_id');
    }

    public function to_account(){
        return $this->belongsTo('App\AccountInformation', 'to_account_id');
    }

}
