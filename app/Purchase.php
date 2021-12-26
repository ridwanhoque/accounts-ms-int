<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Formatter;

class Purchase extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();

        static::creating(function($model){
            $currentUser = Auth::user();
            $purchase_max_id = Purchase::max('id')+1;
            $purchase_reference = Formatter::code('p', $purchase_max_id);

            $model->fill([
                'company_id' => $currentUser->company_id,
                'created_by' => $currentUser->id,
                'updated_by' => $currentUser->id,
                'purchase_reference' => $purchase_reference
            ]);
        });

        static::updating(function($model){
            $currentUser = Auth::user();

            $model->fill([
                'company_id' => $currentUser->company_id,
                'created_by' => $currentUser->id,
                'updated_by' => $currentUser->id
            ]);
        });

        static::deleting(function($purchase){
            $purchase->purchase_details()->delete();
        });

        // static::deleting(function($purchase){
        //     $purchase->purchase_details()->each(function($pdetails){
        //         $pdetails->delete();
        //     });    
        // });

    }


    public function purchase_details(){
        return $this->hasMany('App\PurchaseDetails');
    }

    public function supplier(){
        return $this->belongsTo('App\Supplier');
    }

    public function party(){
        return $this->belongsTo('App\Party');
    }

    public function status(){
        return $this->belongsTo('App\Status');
    }

    public function company(){
        return $this->belongsTo('App\Company');
    }

    public function chart_of_account(){
        return $this->belongsTo('App\ChartOfAccount');
    }

    public function scopeStatuses($q){
        return $q->where('company_id', 1)->get();
    }


    public function purchase_receives(){
        return $this->hasMany('App\PurchaseReceive');
    }

    public function setInvoiceTaxAttribute($value){
        return $this->attributes['invoice_tax'] = $this->zero_fill($value);
    }

    public function setInvoiceDiscountAttribute($value){
        return $this->attributes['invoice_discount'] = $this->zero_fill($value);
     }

    public function zero_fill($value){
        return $value == null ? 0:$value;
    }

    public function batch(){
        return $this->belongsTo('App\Batch');
    }    

    public function transactions(){
        return $this->morphMany('App\Transaction', 'transactionable');
    }
}
