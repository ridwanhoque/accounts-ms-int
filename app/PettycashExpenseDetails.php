<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;


class PettycashExpenseDetails extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();

        static::creating(function($pettycash_expense_details){
            $currentUser = Auth::user();

            $pettycash_expense_details->fill([
                'company_id' => $currentUser->company_id,
                'created_by' => $currentUser->id,
                'updated_by' => $currentUser->id
            ]);

        });

        static::updating(function($pettycash_expense_details){
            $currentUser = Auth::user();

            $pettycash_expense_details->fill([
                'company_id' => $currentUser->company_id,
                'updated_by' => $currentUser->id
            ]);
        });
    }

    public function pettycash_expense(){
        return $this->belognsTo('App\PettycashExpense');
    }

    public function pettycash_chart(){
        return $this->belongsTo('App\PettycashChart');
    }
}
