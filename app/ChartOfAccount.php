<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App;
use Illuminate\Support\Facades\App as FacadesApp;

class ChartOfAccount extends Model
{
    protected $guarded = [];



    public static function boot()
    {
        parent::boot();

        if(!app()->runningInConsole()){

            static::creating(function ($model){
                $model->fill([
                    'company_id' => 1,
                    'created_by' => auth()->id(),
                    'updated_by' => Auth::user()->id,
                    'created_at' => now(),
                    'updated_at' => now(),
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

    public function scopeCharts($q){
        $currentUser = Auth::user();
        return $q->where('company_id', $currentUser->company_id)->get();
    }


    public function created_user(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function updated_user(){
        return $this->belongsTo(User::class,'updated_by');
    }

    public function voucher_account_charts(){
        return $this->hasMany(VoucherAccountChart::class);
    }

    public function cash_flow(){
        
        $from_date = date('Y-m-d');

        return $this->voucher_account_charts()
        ->selectRaw('sum(transections.amount) as sum_amount, voucher_account_charts.chart_of_account_id')
        ->join('transections', function($q){
            $q->on('transections.transactionable_id', '=', 'voucher_account_charts.id')
            ->where('transections.transactionable_type', '=', 'App\VoucherAccountChart');
        })
        ->groupBy('voucher_account_charts.chart_of_account_id') 
            ;
    }

	public function opening_asset(){
		return $this->hasOne('App\OpeningAsset');
    }
    
    public function parent(){
        return $this->belongsTo('App\ChartOfAccount', 'parent_id');
    }
    
    public function child(){
        return $this->hasOne('App\ChartOfAccount', 'parent_id');
    }

    public function parents(){
        return $this->belongsTo('App\ChartOfAccount', 'id', 'parent_id');
    }

    public function children(){
        return $this->hasMany('App\ChartOfAccount', 'parent_id');
    }

    public function setOpeningBalanceAttribute($value){
        return $this->attributes['opening_balance'] = $value > 0 ? $value:0;
    }

    public function chart_type(){
        return $this->belongsTo('App\ChartType');
    }

    public function transaction_details(){
        return $this->hasMany('App\TransactionDetails');
    }

    //<newly added>
    public function setBalanceAttribute($value){
        return $this->attributes['balance'] = $value > 0 ? $value:0;
    }

    public function payment_vouchers()
    {
        return $this->belongsTo('App\PaymentVoucher');
    }

    public function payment_voucher_details()
    {
        return $this->belongsTo('App\PaymentVoucherDetails');
    }
	
	public function receive_vouchers()
    {
        return $this->belongsTo('App\ReceiveVoucher');
    }

    public function receive_voucher_details()
    {
        return $this->belongsTo('App\ReceiveVoucherDetails');
    }
	
	public function journal_vouchers()
    {
        return $this->belongsTo('App\JournalVoucher');
    }

    public function journal_voucher_details()
    {
        return $this->belongsTo('App\JournalVoucherDetails');
    }
	
	public function contra_vouchers()
    {
        return $this->belongsTo('App\ContraVoucher');
    }

    public function contra_voucher_details()
    {
        return $this->belongsTo('App\ContraVoucherDetails');
    }
	
	public function scopeNoChild($q)
    {
        return $q->whereNotIn('id', $q->where('parent_id', '>', 0)->distinct()->pluck('parent_id'));
    }

    public function owner_type(){
        return $this->belongsTo('App\OwnerType');
    }

    public function chart_of_account_balance(){
        return $this->hasMany('App\ChartOfAccountBalance');
    }
    
	public function asset_sum(){
        return $this->transaction_details()->selectRaw('sum(amount) sumAmount, chart_of_account_id')->groupBy('chart_of_account_id');
    }
    //</newly added>
}
