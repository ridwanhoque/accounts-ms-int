<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App;

class AccountInformation extends Model
{
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        if(!App::runningInConsole()){
            static::creating(function ($model){
                $model->fill([
                    'company_id' => Auth::user()->company_id,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ]);
            });
            static::updating(function ($model){
                $model->fill([
                    'updated_by' => Auth::user()->id,
                ]);
            });
    
        }
    }

    public function payment_methods(){
        return $this->hasMany(PaymentMethod::class);
    }


    public function created_user(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function updated_user(){
        return $this->belongsTo(User::class,'updated_by');
    }

    public function scopeAccountInfo($q){
        $currentUser = Auth::user();
        return $q->where('company_id', $currentUser->company_id)->get();
    }


    public function fund_transfer_froms(){
        return $this->hasMany('App\FundTransfer', 'id', 'from_account_id');
    }

    public function fund_transfer_tos(){
        return $this->hasMany('App\FundTransfer', 'id', 'to_account_id');
    }

    public function transections(){
        return $this->hasMany('App\Transection');
    }

    public function journal_vouchers(){
        return $this->hasMany('App\JournalVoucher');
    }

}
