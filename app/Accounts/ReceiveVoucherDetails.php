<?php

namespace App\Accounts;

use Illuminate\Database\Eloquent\Model;

class ReceiveVoucherDetails extends Model
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
    
    public function scopeReceiveVoucherDetails($q){
        return $q->where('company_id', \companyId());
    }

    public function receive_voucher()
    {
        return $this->belongsTo('App\Accounts\ReceiveVoucher');
    }
	
	public function chart_of_account()
    {
        return $this->belongsTo('App\ChartOfAccount');
    }
}
