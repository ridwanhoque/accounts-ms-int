<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class JournalVoucher extends Model
{
    protected $guarded = ['id'];

    public function account_information(){
        return $this->belongsTo('App\AccountInformation');
    }

    public function journalable(){
        return $this->morphTo();
    }

    public function scopeJournalVouchers($q){
        $currentUser = Auth::user();
        return $q->where('company_id', $currentUser->company_id);
    }

    public function party(){
        return $this->belongsTo('App\Party');
    }

    //<newly added>
    public function chart_of_account(){
        return $this->belongsTo('App\ChartOfAccount');
    }

    //</newly added>

}
