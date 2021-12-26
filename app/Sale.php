<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Sale;
use App;
use Auth;
use Formatter;

class Sale extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();

        if(!App::runningInConsole()){

            static::creating(function($sale){
                $currentUser = Auth::user();
                $sale_max_id = Sale::max('id')+1;
                $sale->fill([
                    'company_id' => $currentUser->company_id,
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id,
                    'sale_reference' => Formatter::code('sale-', $sale_max_id)
                ]);
            });

            static::updating(function($sale){
                $currentUser = Auth::user();

                $sale->fill([
                    'company_id' => $currentUser->company_id,
                    'updated_by' => $currentUser->id
                ]);
            });

            static::deleting(function($sale){
                $sale->sale_details()->delete();
            });
        }
        
    }

    public function scopeSales($q){
        return $q->where('company_id', Auth::user()->id)->get();
    }

    public function customer(){
        return $this->belongsTo('App\Customer');
    }

    public function status(){
        return $this->belongsTo('App\Status');
    }

    public function company(){
        return $this->belongsTo('App\Company');
    }

    public function sale_details(){
        return $this->hasMany('App\SaleDetails');
    }

    public function party(){
        return $this->belongsTo('App\Party');
    }

    public function chart_of_account(){
        return $this->belongsTo('App\ChartOfAccount');
    }

    public function product_delivery_details(){
        return $this->hasMany('App\ProductDeliveryDetails');
    }

    public function setInvoiceVatAttribute($value){
        return $this->attributes['invoice_vat'] = $value > 0 ? $value:0;
    }

    public function setInvoiceTaxAttribute($value){
        return $this->attributes['invoice_tax'] = $value > 0 ? $value:0;
    }

    public function transactions(){
        return $this->morphMany('App\Transaction', 'transactionable');
    }

    public function setTaxPercentAttribute($value){
        return $this->attributes['tax_percent'] = $value > 0 ? $value:0;
    }
}
