<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use Auth;

class PettycashChart extends Model
{
    protected $guarded = ['id'];

    
    public static function boot(){
        parent::boot();

        if(!App::runningInConsole()){
            static::creating(function($pettycash_chart){
                $currentUser = Auth::user();

                $pettycash_chart->fill([
                    'company_id' => $currentUser->company_id,
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id
                ]);

            });

            static::updating(function($pettycash_chart){
                $currentUser = Auth::user();

                $pettycash_chart->fill([
                    'company_id' => $currentUser->company_id,
                    'updated_by' => $currentUser->id
                ]);


            });
        }
    }

    public function charts($q){
        $currentUser = Auth::user();
        return $q->where('company_id', $currentUser->company_id)->get();
    }

    public function pettycash_expense_details(){
        return $this->hasMany('App\PettycashExpenseDetails');
    }
}
