<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionDetails extends Model
{
    protected $guarded = ['id'];

    public function transaction(){
        return $this->belongsTo('App\Transaction');
    }

    public function chart_of_account(){
        return $this->belongsTo('App\ChartOfAccount');
    }
}
