<?php

namespace App\Accounts;

use Illuminate\Database\Eloquent\Model;

class JournalVoucher extends Model
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
    }

    public function scopeJournalVoucher($q){
        return $q->where('company_id', \companyId());
    }

    public function journal_voucher_details(){
        return $this->hasMany('App\Accounts\JournalVoucherDetails');
    }
    
    public function chart_of_account()
    {
        return $this->belongsTo('App\ChartOfAccount');
    }
    
    public function company()
    {
        return $this->belongsTo('App\Company');
    }

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

}
