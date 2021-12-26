<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class VoucherAccountChart extends Model
{

    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->fill([
                'company_id' => 1,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        });
        static::updating(function ($model) {
            $model->fill([
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now(),
            ]);
        });
    }

    public function chart_of_account()
    {
        return $this->belongsTo(ChartOfAccount::class);
    }

    public function voucher()
    {
        return $this->belongsTo('App\Voucher');
    }

    public function voucher_chart_payments()
    {
        return $this->hasMany('App\VoucherChartPayment');
    }

    public function transactions()
    {
        return $this->morphMany('App\Transection', 'transactionable');
    }

    public function income_this_month()
    {
        $this_month = Carbon::now()->startOfMonth();
        $data = $this->transactions()
            ->where('amount', '>', 0)
            ->where('created_at', '>=', $this_month)
            ->groupBy('transactionable_type')
            ->sum('amount');

        return count($data) > 0 ? $data : 0;
    }

    public function this_month_income()
    {
        $this_month = Carbon::now()->startOfMonth();
        return $this->hasOne('App\Transection', 'transactionable_id')
            ->where('created_at', '>', $this_month)
            ->selectRaw('sum(amount) as sum_amount, transactionable_type')
            ->groupBy('transactionable_type');
    }
    // rwjezv4z
    public function previous_month_income()
    {
        $this_month = Carbon::now()->startOfMonth();
        $previous_month = Carbon::now()->subMonth()->startOfMonth();

        return $this->hasOne('App\Transection', 'transactionable_id')
            ->where('created_at', '>', $previous_month)
            ->where('created_at', '<', $this_month)
            ->selectRaw('sum(amount) as sum_amount, transactionable_type')
            ->groupBy('transactionable_type');
    }

}
