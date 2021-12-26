<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App;

class SubRawMaterial extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();


        if(!App::runningInConsole()){

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

    }


    public function raw_material(){
        return $this->belongsTo('App\RawMaterial');
    }

    public function purchase_details(){
        return $this->belongsTo('App\PurchaseDetails');
    }

    public function purchase_receive_details(){
        return $this->hasMany('App\PurchaseReceiveDetails');
    }

    public function raw_material_stocks(){
        return $this->hasMany('App\RawMaterialStock');
    }

    public function sheets(){
        return $this->hasMany('App\Sheet');
    }

    public function sheet_wastage_stocks(){
        return $this->hasMany('App\SheetWastageStock');
    }

    public function scopeSubRawMaterials($q){
        $currentUser = Auth::user();
        return $q->where('company_id', $currentUser->company_id)->get();
    }

    public function sheet_stock(){
        return $this->hasOne('App\SheetStock');
    }

    public function direct_production_sheets(){
        return $this->morphMany('App\DirectProductionSheet', 'direct_production_sheetable');
    }

	public function rm_bags(){
		return $this->hasOne('App\PurchaseReceiveDetails')->groupBy('sub_raw_material_id')
				->selectRaw('sum(quantity_bag) as bags, sub_raw_material_id')->pluck('bags');
    }
    
    public function haddi_powder_stocks(){
        return $this->hasMany('App\HaddiPowderStock');
    }

    public function raw_material_batch_stocks(){
        return $this->hasMany('App\RawMaterialBatchStock');
    }

    public function temporary_sheet_productions(){
        return $this->hasMany('App\TemporarySheetProduction');
    }

    public function temporary_direct_productions(){
        return $this->hasMany('App\TemporaryDirectProduction');
    }
}