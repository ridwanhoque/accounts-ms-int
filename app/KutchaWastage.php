<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App;

class KutchaWastage extends Model
{
    protected $guarded = ['id'];
	
	public static function boot(){
        parent::boot();

        if(!App::runningInConsole()){
            static::creating(function($kutcha_wastage){
                
                $currentUser = Auth::user();
                
                $kutcha_wastage->fill([
                    'company_id' => $currentUser->company_id,
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id,
                ]);
            });

            static::updating(function($kutcha_wastage){

                $currentUser = Auth::user();

                $kutcha_wastage->fill([
                    'company_id' => $currentUser->company_id,
                    'updated_by' => $currentUser->id,
                ]);

            });
        }
    }

    public function scopeKutchaWastages($q){
        $currentUser = Auth::user();
        return $q->where('company_id', $currentUser->company_id)->get();
    }

    public function sheet_production(){
        return $this->belongsTo('App\SheetProduction');
    }

    public function fm_kutcha(){
        return $this->belongsTo('App\FmKutcha');
    }

    public function kutcha_qty_kg(){
        return $this->selectRaw('sum(qty_kg) as sum_qty, fm_kutcha_id')->groupBy('fm_kutcha_id');
    }
    
    public function scopeFmkutchaOpening($q, $fm_kutcha_id, $from_date, $to_date){
        return $q->where('fm_kutcha_id', $fm_kutcha_id)
            ->where('created_at', '>=', $from_date)
            ->where('created_at', '<=', $to_date);
    }
    
}
