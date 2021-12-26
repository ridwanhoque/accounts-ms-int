<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use Auth;

class Sheet extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();

        if(!App::runningInConsole()){
            static::creating(function($sheet){
                $currentUser = Auth::user();

                $sheet->fill([
                    'company_id' => $currentUser->company_id,
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id
                ]);
            });

            static::updating(function($sheet){
                $currentUser = Auth::user();

                $sheet->fill([
                    'company_id' => $currentUser->company_id,
                    'updated_by' => $currentUser->id
                ]);
            });
        }
    }

    public function scopeSheets($q){
        $currentUser = Auth::user();
        return $q->where('company_id', $currentUser->company_id);
    }

    public function sheet_production_details(){
        return $this->belongsTo('App\SheetProductionDetails');
    }

    public function sub_raw_material(){
        return $this->belongsTo('App\SubRawMaterial');
    }

    //fill fields with zero value if user skip
    public function setWastageOutAttribute($value){
        return $this->attributes['wastage_out'] = $this->zero_fill($value);
    }

    public function setSheetWastageAttribute($value){
        return $this->attributes['sheet_wastage'] = $this->zero_fill($value);
    }

    public function setFormingWastageAttribute($value){
        return $this->attributes['forming_wastage'] = $this->zero_fill($value);
    }

    public function zero_fill($value){
        return $value == null ? 0:$value;
    }

    public function sheet_production(){
        return $this->belongsTo('App\SheetProduction');
    }

    public function fm_kutcha(){
        return $this->belongsTo('App\FmKutcha');
    }

    public function batch(){
        return $this->belongsTo('App\Batch');
    }

}
