<?php

namespace App\Accounts;

use Illuminate\Database\Eloquent\Model;

class PaymentVoucherDetails extends Model
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

    public function scopePaymentVoucherDetails($q){
        return $q->where('company_id', \companyId());
    }

    public function payment_voucher(){
        return $this->belongsTo('App\PaymentVoucher');
    }

    public function chart_of_account()
    {
        return $this->belongsTo('App\ChartOfAccount');
    }
}
