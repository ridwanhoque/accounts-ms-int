<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PaymentMethod extends Model
{
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
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

    public function scopeMethods($q){
        $currentUser = Auth::user();
        return $q->where('company_id', $currentUser->company_id);
    }

    public function account_information(){
        return $this->belongsTo(AccountInformation::class);
    }

    public function created_user(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function updated_user(){
        return $this->belongsTo(User::class,'updated_by');
    }
}
