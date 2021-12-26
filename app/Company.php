<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Founcation;
use  App;

class Company extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();

        if(!App::runningInConsole()){
            static::creating(function($model){
                $currentUser = Auth::user()->id;
    
                $model->fill([
                    'created_by' => $currentUser,
                    'updated_by' => $currentUser
                ]);
            });
    
            static::updating(function($model){
                $currentUser = Auth::user()->id;
    
                $model->fill([
                    'created_by' => $currentUser,
                    'updated_by' => $currentUser
                ]);
            });
        }
    }


    public function purchases(){
        return $this->hasMany('App\Purchase');
    }

    public function sales(){
        return $this->hasMany('App/Sale');
    }

    public function pettycash_expenses(){
        return $this->hasMany('App\PettycashExpense');
    }

    public function users(){
        return $this->hasMany('App\User');
    }

    public function sheet_productions(){
        return $this->hasMany('App\SheetProduction');
    }

    public function direct_productions(){
        return $this->hasMany('App\DirectProduction');
    }

    public function daily_productions(){
        return $this->hasMany('App\DailyProduction');
    }

    public function sale_quotations(){
        return $this->hasMany('App\SaleQuotation');
    }

    public function temporary_sheet_productions(){
        return $this->hasMany('App\TemporarySheetProduction');
    }

    public function temporary_direct_productions(){
        return $this->hasMany('App\TemporaryDirectProduction');
    }

    public function product_stock_transfers(){
        return $this->hasMany('App\ProductStockTransfer');
    }

    public function payment_vouchers()
    {
        return $this->hasMany('App\PaymentVoucher');
    }
	
    public function receive_vouchers()
    {
        return $this->hasMany('App\ReceiveVoucher');
    }
	
	public function journal_vouchers()
    {
        return $this->hasMany('App\JournalVoucher');
    }
	
    public function contra_vouchers()
    {
        return $this->hasMany('App\ContraVoucher');
    }
}
