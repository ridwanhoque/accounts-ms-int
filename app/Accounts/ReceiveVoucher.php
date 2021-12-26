<?php

namespace App\Accounts;

use Illuminate\Database\Eloquent\Model;

class ReceiveVoucher extends Model
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

    public function scopeReceiveVoucher($q){
        return $q->where('company_id', \companyId());
    }
    
    public function receive_voucher_details()
    {
        return $this->hasMany('App\Accounts\ReceiveVoucherDetails');
    }
	
	public function chart_of_account()
    {
        return $this->belongsTo('App\ChartOfAccount');
    }
    
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
}
