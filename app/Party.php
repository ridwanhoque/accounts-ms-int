<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App;

class Party extends Model
{
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        if(!App::runningInConsole()){
            static::creating(function ($model){
                $model->fill([
                    'company_id' => 1,
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
    }

    public function scopeParties($q){
        $current_user = Auth::user();
        return $q->where('company_id', $current_user->company_id)->get();
    }

    public function purchases(){
        return $this->hasMany('App\Purchase');
    }

    public function sales(){
        return $this->hasMany('App\Sale');
    }

    public function product_deliveries(){
        return $this->hasMany('App\ProductDelivery');
    }

    public function sale_quotations(){
        return $this->hasMany('App\SaleQuotation');
    }

    public function assets(){
        return $this->hasMany('App\Asset');
    }

    public function setOpeningBalanceAttribute($value){
        return $this->attributes['opening_balance'] = $value < 0 ?: 0; 
    }

    public function journal_vouchers(){
        return $this->hasMany('App\JournalVoucher');
    }
}
