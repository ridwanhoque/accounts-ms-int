<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use Auth;

class PettycashDeposit extends Model
{
    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();
        if (!App::runningInConsole()) {

            static::creating(function ($pettycash_deposit) {
                $currentUser = Auth::user();

                $pettycash_deposit->fill([
                    'company_id' => $currentUser->company_id,
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id
                ]);
            });

            static::updating(function ($pettycash_deposit) {
                $currentUser = Auth::user();

                $pettycash_deposit->fill([
                    'company_id' => $currentUser->company_id,
                    'updated_by' => $currentUser->id
                ]);
            });

        }
    }

    public function received_by_user(){
        return $this->belongsTo('App\User', 'received_by');
    }

    public function transactions(){
        return $this->hasMany('App\Transection');
    }

    

}
