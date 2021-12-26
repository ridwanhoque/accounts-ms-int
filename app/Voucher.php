<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Voucher extends Model
{

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model){
            $model->fill([
                'company_id' => Auth::user()->company_id,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        });
        static::updating(function ($model){
            $model->fill([
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now(),
            ]);
        });
    }

    public function voucher_account_charts(){
        return $this->hasMany(VoucherAccountChart::class);
    }

    public function voucher_chart_payment(){
        return $this->hasOne(VoucherChartPayment::class);
    }

    public function voucher_payment(){
        return $this->hasMany('App\VoucherPayment');
    }

    public function party(){
        return $this->belongsTo(Party::class);
    }

    public function user(){
        return $this->belongsTo(User::class,'created_by');
    }

//
//    public function account_information(){
//        return $this->belongsTo(AccountInformation::class);
//    }
    public function thisMonthReceivable($party_id, $due = null)
    {
        $this_month = Carbon::now()->startOfMonth();
        
        if($due == 'due'){
            $data = $this->where('voucher_type', 'credit')
            ->where('due_amount', '>', 0)
            ->where('voucher_date', '>', $this_month)
            ->where('party_id', $party_id);
        }else{
            $data = $this->where('voucher_type', 'debit')
            ->where('due_amount', '>', 0)
            ->where('voucher_date', '>', $this_month)
            ->where('party_id', $party_id);
        }
            
            
        if(count($data) >=1 ){
            return $data->sum('due_amount');
        }
        return 0.00;
    }

    public function previousMonthReceivable($party_id, $due = null){
        $this_month = Carbon::now()->startOfMonth();
        $previous_month = Carbon::now()->startOfMonth()->subMonth();
        
        if($due == 'due'){
            $data = $this->where('voucher_type', 'credit')
            ->where('due_amount', '>', 0)
            ->where('voucher_date', '>=', $previous_month)
            ->where('voucher_date', '<', $this_month)
            ->where('party_id', $party_id);
        }else{
            $data = $this->where('voucher_type', 'debit')
            ->where('due_amount', '>', 0)
            ->where('voucher_date', '>=', $previous_month)
            ->where('voucher_date', '<', $this_month)
            ->where('party_id', $party_id);
        }
        
        
        
            if(count($data) >=1){
            return $data->sum('due_amount');
        }
        return 0.00;
    }

}
