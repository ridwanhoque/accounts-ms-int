<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AssetDetails extends Model
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

    public function asset(){
        return $this->belongsTo('App\Asset');
    }

    public function asset_chart(){
        return $this->belongsTo('App\AssetChart');
    }

    public function transactions(){
        return $this->morphMany('App\Transection', 'transactionable');
    }

    public function chart_of_account(){
        return $this->belongsTo('App\ChartOfAccount');
    }
}
