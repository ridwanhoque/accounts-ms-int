<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Formatter;
use Auth;
use App;

class Supplier extends Model
{
    protected $guarded = ['id'];

   

    public static function boot(){
        parent::boot();

        if(!App::runningInConsole()){

            static::creating(function($model){
                $currentUser = Auth::user();
                $supplier_max_id = Supplier::max('id')+1;
                $supplier_code_db = Formatter::code('s', $supplier_max_id);
    
                $model->fill([
                    'company_id' => $currentUser->company_id,
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id,
                    'supplier_code' => $supplier_code_db
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

    public function purchases(){
        return $this->hasMany('App\Purchase');
    }

    public function scopeSuppliers($q){
        return $q->where('company_id', Auth::user()->id)->get();
    }


}
