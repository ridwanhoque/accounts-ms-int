<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App;

class FmKutcha extends Model
{
    protected $guarded = ['id'];

	public static function boot()
    {
        parent::boot();

        if(!App::runningInConsole()){
            static::creating(function ($model){
                $model->fill([
                    'company_id' => Auth::user()->company_id,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ]);
            });
            static::updating(function ($model){
                $model->fill([
                    'updated_by' => Auth::user()->id,
                ]);
            });
    
        }
    }
	
	public function raw_material(){
		return $this->belongsTo('App\RawMaterial');
	}

	public function scopeFmKutchas($q){
		$currentUser = Auth::user();
		return $q->where('company_id', $currentUser->company_id)->get();
	}	
	
    public function kutcha_wastages(){
        return $this->hasMany('App\KutchaWastage');
    }

    public function kutcha_wastage_stock(){
        return $this->hasOne('App\KutchaWastageStock');
    }

    public function sheets(){
        return $this->hasMany('App\Sheet');
    }

    public function daily_production_details(){
        return $this->hasMany('App\DailyProductionDetail');
    }

    public function direct_production_details(){
        return $this->hasMany('App\DirectProductionDetails');
    }

    public function direct_production_sheets(){
        return $this->morphMany('App\DirectProductionSheet', 'direct_production_sheetable');
    }

    public function fm_kutcha_sheet_sum(){
        return $this->hasOne('App\Sheet')->selectRaw('sum(qty_kg) as sum_qty_kg, fm_kutcha_id')
            ->groupBy('fm_kutcha_id');
    }

    public function fm_kutcha_out_sum(){
        return $this->hasOne('App\KutchaWastage')->selectRaw('sum(qty_kg) as sum_qty_kg, fm_kutcha_id')
            ->groupBy('fm_kutcha_id');
    }

    public function fm_kutcha_day_opening(){
        return $this->hasOne('App\KutchaWastage')->selectRaw('sum(qty_kg) as opening_qty, fm_kutcha_id')
                ->groupBy('fm_kutcha_id');
    }

    public function fm_kutcha_opening(){
        return $this->hasOne('App\KutchaWastageStock')->selectRaw('opening_kg, fm_kutcha_id');
    }

    public function fm_kutcha_opening_stock(){
        return $this->hasOne('App\KutchaWastageStock')->selectRaw('opening_kg, fm_kutcha_id');
    }

    public function fm_kutcha_deduction_till_now(){
        
    }

    // public function sum_kutcha_direct_production(){
    //     return $this->hasOne('App\DailyProductionDetail')
    //          ->selectRaw('sum(wastage_out) as sum_kutcha, fm_kutcha_id')
    //          ->groupBy('fm_kutcha_id');
    // }

    public function sum_kutcha_daily_production(){
        return $this->hasOne('App\DailyProductionDetail')
             ->selectRaw('sum(wastage_out) as sum_kutcha, fm_kutcha_id')
             ->groupBy('fm_kutcha_id');
    }
	
    public function sum_kutcha_sheet_production(){
        return $this->hasOne('App\KutchaWastage')
            ->selectRaw('sum(qty_kg) as sum_kutcha, fm_kutcha_id')
            ->groupBy('fm_kutcha_id');
    }

    public function sum_kutcha_sheet_production_in(){
        return $this->hasOne('App\Sheet')
            ->where('fm_kutcha_id', '>', 0)
            ->selectRaw('sum(qty_kg) as sum_kutcha, fm_kutcha_id')
            ->groupBy('fm_kutcha_id');
    }

    public function sum_kutcha_direct_production(){
        return $this->hasOne('App\DirectProductionDetails')
            ->selectRaw('sum(kutcha_qty) as sum_kutcha, fm_kutcha_id')
            ->groupBy('fm_kutcha_id');
    }
    
    public function sum_kutcha_direct_production_in(){
        return $this->hasOne('App\DirectProductionSheet', 'direct_production_sheetable_id', 'id')
            ->where('direct_production_sheetable_type', 'App\FmKutcha')
            ->selectRaw('sum(qty_kg) as sum_kutcha, direct_production_sheetable_id')
            ->groupBy('direct_production_sheetable_id');
    }

    public function temporary_sheet_productions(){
        return $this->hasMany('App\TemporarySheetProduction');
    }
    
    public function temporary_direct_productions(){
        return $this->hasMany('App\TemporaryDirectProduction');
    }

}