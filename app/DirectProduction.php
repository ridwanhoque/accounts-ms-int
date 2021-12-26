<?php

namespace App;

use App;
use Auth;
use Illuminate\Database\Eloquent\Model;

class DirectProduction extends Model
{
    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();

        if (!App::runningInConsole()) {
            static::creating(function ($model) {

                $currentUser = Auth::user();

                $model->fill([
                    'company_id' => $currentUser->company_id,
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id,
                ]);
            });

            static::updating(function ($model) {

                $currentUser = Auth::user();

                $model->fill([
                    'company_id' => $currentUser->company_id,
                    'updated_by' => $currentUser->id,
                ]);
            });
        }
    }

    public function company(){
        return $this->belongsTo('App\Company');
    }

    public function batch(){
        return $this->belongsTo('App\Batch');
    }

    public function direct_production_details(){
        return $this->hasMany('App\DirectProductionDetails');
    }

    public function direct_production_sheets(){
        return $this->hasMany('App\DirectProductionSheet');
    }

    public function total_kutchas(){
        return $this->hasOne('App\DirectProductionDetails')
                    ->selectRaw('sum(kutcha_qty) as sum_kutcha, direct_production_id')
                    ->groupBy('direct_production_id');
    }

}
