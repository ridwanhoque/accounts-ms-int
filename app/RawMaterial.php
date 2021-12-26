<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App;

class RawMaterial extends Model
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


    public function purchase_details(){
        return $this->hasMany('App\PurchaseDetails');
    }

    public function raw_material_stocks(){
        return $this->hasMany('App\RawMaterialStock');
    }

    public function raw_material_available(){
        return $this->raw_material_stocks()->where('available_quantity','>','0');
    }

    public function sheets(){
        return $this->hasMany('App\Sheets');
    }

    public function raw_material_with_purchase(){
        return $this->raw_material_stocks()->where('purchased_quantity','>', '0');
    }

    public function scopeRawMaterials($q){
        return $q->where('company_id', Auth::user()->company_id)->get();
    }

    public function sheet_wastage_stock(){
        return $this->hasOne('App\SheetWastageStock');
    }

    public function purchase_receive_details(){
        return $this->hasMany('App\PurchaseReceiveDetails');
    }

    public function sub_raw_materials(){
        return $this->hasMany('App\SubRawMaterial');
    }

    public function sheet_sizes(){
        return $this->hasMany('App\SheetSize');
    }
	
	public function fm_kutchas(){
		return $this->hasMany('App\FmKutcha');
	}

    public function products(){
		return $this->hasMany('App\Product');
	}

    public function sheet_size_colors(){
        return $this->hasMany('App\SheetSizeColor');
    }

    public function config_materials(){
        return $this->hasMany('App\ConfigMaterial');
    }

}
