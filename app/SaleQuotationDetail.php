<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class SaleQuotationDetail extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();

        static::creating(function($model){
            $currentUser = Auth::user();

            $model->fill([
                'company_id' => $currentUser->company_id,
                'created_by' => $currentUser->id,
                'updated_by' => $currentUser->id
            ]);
        });


        static::updating(function($model){
            $currentUser = Auth::user();

            $model->fill([
                'company_id' => $currentUser->company_id,
                'updated_by' => $currentUser->id
            ]);
        });

    }


    public function sale_quotation(){
        return $this->belongsTo('App\SaleQuotation');
    }

    public function product(){
        return $this->belongsTo('App\Product');
    }


}
