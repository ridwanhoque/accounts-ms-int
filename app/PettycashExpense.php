<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PettycashExpense;
use Auth;
use Formatter;

class PettycashExpense extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();

        static::creating(function($pettycashExpense){
            $currentUser = Auth::user();
            $max_pettycash_expense_id = PettycashExpense::max('id');

            $pettycashExpense->fill([
                'company_id' => $currentUser->company_id,
                'created_by' => $currentUser->id,
                'updated_by' => $currentUser->id,
                'pettycash_expense_reference' => Formatter::code('', $max_pettycash_expense_id)
            ]);

        });

        static::updating(function($pettycashExpense){
            $currentUser = Auth::user();

            $pettycashExpense->fill([
                'company_id' => $currentUser->company_id,
                'updated_by' => $currentUser->id
            ]);
        });
    }

    public function pettycash_expense_details(){
        return $this->hasMany('App\PettycashExpenseDetails');
    }

    public function company(){
        return $this->belongsTo('App\Company');
    }

    public function scopePettycashExpenseInfo($q){
        $currentUser = Auth::user();
        return $q->where('company_id', $currentUser->company_id)->get();
    }
}
