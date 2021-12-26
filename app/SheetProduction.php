<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use Auth;
use Formatter;
use Arr;

class SheetProduction extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();

        if(!App::runningInConsole()){
            static::creating(function($sheet_production){
                
                $currentUser = Auth::user();
                $max_sheet_production_id = SheetProduction::max('id') + 1;
                
                $sheet_production->fill([
                    'company_id' => $currentUser->company_id,
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id,
                    'sheet_production_reference' => Formatter::code('shprod', $max_sheet_production_id) 
                ]);
            });

            static::updating(function($sheet_production){

                $currentUser = Auth::user();

                $sheet_production->fill([
                    'company_id' => $currentUser->company_id,
                    'updated_by' => $currentUser->id,
                ]);

            });
        }
    }

    public function batch(){
        return $this->belongsTo('App\Batch');
    }

    public function status(){
        return $this->belongsTo('App\Status');
    }

    public function sheet_production_details(){
        return $this->hasMany('App\SheetProductionDetails');
    }  

    public function sheets(){
        return $this->hasMany('App\Sheet');
    }

    public function kutcha_wastages(){
        return $this->hasMany('App\KutchaWastage');
    }

    public function setTotalKgAttribute($value){
        return $this->attributes['total_kg'] = $value > 0 ? $value:0; 
    }

    public function setTotalRollAttribute($value){
        return $this->attributes['total_roll'] = $value > 0 ? $value:0;
    }

    public function setPowderAttribute($value){
        return $this->attributes['powder'] = $value > 0 ? $value:0;
    }

    public function setHaddiAttribute($value){
        return $this->attributes['haddi'] = $value > 0 ? $value:0;
    }

    public function sum_material(){
        return $this->hasOne('App\Sheet')->selectRaw('sum(qty_kg) as total_kg_material, sheet_production_id')->groupBy('sheet_production_id');
    }

    public function raw_material_kg(){
        return Arr::get($this->sum_material, 'total_kg_material', 0);
    }

    public function sum_sheet(){
        return $this->hasOne('App\SheetProductionDetails')->selectRaw('sum(qty_kg) as total_kg_sheet, sheet_production_id')->groupBy('sheet_production_id');
    }

    public function sheet_kg(){
        return Arr::get($this->sum_sheet, 'total_kg_sheet', 0);
    }

    public function sum_kutcha(){
        return $this->hasOne('App\KutchaWastage')->selectRaw('sum(qty_kg) as total_kg_kutcha, sheet_production_id')->groupBy('sheet_production_id');
    }

    public function kutcha_kg(){
        return Arr::get($this->sum_kutcha, 'total_kg_kutcha', 0);
    }

    public function company(){
        return $this->belongsTo('App\Company');
    }

    public function scopeTempSheetProductions($q){
        $currentUser = Auth::user();
        return $q->where('company_id', $currentUser->company_id);
    }

}
