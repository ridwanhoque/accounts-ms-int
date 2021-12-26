<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = ['id'];

    public static function boot(){

        parent::boot();

        static::creating(function($model){
            $model->fill([
                'company_id' => auth()->user()->company_id,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id
            ]);
        });

        static::updating(function($model){
            $model->fill([
                'company_id' => auth()->user()->company_id,
                'updated_by' => auth()->user()->id
            ]);
        });

        // static::deleting(function($transaction){
        //     foreach($transaction->transaction_details as $tdetails){
        //         $tdetails->delete();
        //     }
        // });

    }

    public function transaction_details(){
        return $this->hasMany('App\TransactionDetails');
    }

    public function transactionable(){
        return $this->morphTo();
    }
}
