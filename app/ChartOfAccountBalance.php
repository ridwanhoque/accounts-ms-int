<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccountBalance extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();

        static::creating(function($model){
            $model->fill([
                'company_id' => auth()->user()->company_id,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id()
            ]);
        });

        static::updating(function($model){
            $model->fill([
                'company_id' => auth()->user()->company_id,
                'updated_by' => auth()->id()
            ]);
        });
    }

    public function chart_of_account(){
        return $this->belongsTo('App\ChartOfAccount');
    }
}
