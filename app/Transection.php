<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use Auth;

class Transection extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();

        static::creating(function($transection){
            $currentUser = Auth::user();

            $transection->fill([
                'company_id' => $currentUser->company_id,
                'created_by' => $currentUser->id,
                'updated_by' => $currentUser->id
            ]);
        });

        static::updating(function($transection){
            $currentUser = Auth::user();

            $transection->fill([
                'company_id' => $currentUser->company_id,
                'updated_by' => $currentUser->id
            ]);
        });


    }

    public function transactionable(){
        return $this->morphTo();
    }

    public function account_information(){
        return $this->belongsTo('App\AccountInformation');
    }
    
    public function scopeTransectionInfo($q){
        $currentUser = Auth::user();
        return $q->where('company_id', $currentUser->company_id)->get();
    }


}
