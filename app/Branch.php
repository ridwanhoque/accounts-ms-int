<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use Auth;

class Branch extends Model
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


    public function scopeBranches($q){
        return $q->where('company_id', auth()->user()->company_id);
    }

    public function product_stock_transfer_from_branch(){
        return $this->hasMany('App\ProductStockTransfer', 'id', 'from_branch');
    }

    public function product_stock_transfer_to_branch(){
        return $this->hasMany('App\ProductStockTransfer', 'id', 'to_branch');
    }


    public function product_branch_stocks(){
        return $this->hasMany('App\ProductBranchStock');
    }

}
