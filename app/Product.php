<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App;

class Product extends Model
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
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id
                ]);
            });    
        }
    }

    public function daily_production_details(){
        return $this->hasMany('App\DailyProductionDetail');
    }

    public function product_stock(){
        return $this->hasOne('App\ProductStock');
    }

    public function sale_details(){
        return $this->hasMany('App\SaleDetails');
    }

    public function raw_material(){
        return $this->belongsTo('App\RawMaterial');
    }

    public function direct_production_details(){
        return $this->hasMany('App\DirectProductionDetails');
    }

    // public function setNameAttribute($value){
    //     return $this->attributes['name'] = \ucfirst($value);
    // }

    // public function getNameAttribute(){
    //     return $this->attributes['name'] = 10;
    // }

    public function forming_wastage_stocks(){
        return $this->hasMany('App\FormingWastageStock');
    }

    public function machine(){
        return $this->belongsTo('App\Machine');
    }

    public function product_delivery_details(){
        return $this->hasMany('App\ProductDeliveryDetails');
    }

    public function scopeProducts($q){
        $currentUser = Auth::user();
        return $q->where('company_id', $currentUser->company_id)->get();
    }


    public function sale_quotation_details(){
        return $this->hasMany('App\SaleQuotationDetail');
    }
	
	public function produced_kg(){
		return $this->hasOne('App\DailyProductionDetail')->groupBy('product_id')
				->selectRaw('sum(finish_quantity) as qty_pcs, sum(pack) as qty_packs, sum(net_weight) as weight, product_id')->pluck('weight');
	}
	
	public function produced_kg_direct(){
		return $this->hasOne('App\DirectProductionDetails')->groupBy('product_id')
				->selectRaw('sum(finish_quantity) as qty_pcs, sum(pack) as qty_packs, sum(net_weight) as weight, product_id')->pluck('weight');
	}
	
	public function sum_quantity(){
		return $this->produced_kg+$this->produced_kg_direct;
	}

    public function color(){
        return $this->belongsTo('App\Color');
    }

    public function setExpectedQuantityAttribute($value){
        return $this->attributes['expected_quantity'] = $value > 0 ? $value:0;
    }

    public function setStandardWeightAttribute($value){
        return $this->attributes['standard_weight'] = $value > 0 ? $value:0;
    }

    public function product_stock_transfer_details(){
        return $this->hasMany('App\ProductStockTransferDetails');
    }


    public function product_branch_stocks(){
        return $this->hasMany('App\ProductBranchStock');
    }

	public function daily_production_sum(){
        return $this->daily_production_details()->groupBy('product_id')->selectRaw('sum(finish_quantity) as qty_pcs, sum(pack) as qty_packs, sum(net_weight) as qty_weight, product_id');
    }

    public function direct_production_sum(){
        return $this->direct_production_details()->groupBy('product_id')->selectRaw('sum(finish_quantity) as qty_pcs, sum(pack) as qty_packs, sum(net_weight) as qty_weight, product_id');
    }

    public function stock_transfer_sum(){
        return $this->product_stock_transfer_details()->groupBy('product_id')->selectRaw('sum(quantity) as qty_pcs, sum(pack) as qty_packs, sum(weight) as qty_weight, product_id');
    }

    public function product_delivery_sum(){
        return $this->product_delivery_details()->groupBy('product_id')->selectRaw('sum(quantity) as qty_pcs, sum(pack) as qty_packs, sum(weight) as qty_weight, product_id');
    }

}
