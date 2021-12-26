<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App;
use App\Customer;
use Formatter;
// use App\Traits\CreateUpdateBootTrait;

class Customer extends Model
{
    // use CreateUpdateBootTrait;

    

    protected $guarded = ['id'];
  

    public static function boot(){
        parent::boot();

        if(!App::runningInConsole()){
            static::creating(function($customer){
                $currentUser = Auth::user();
                $max_customer_id = Customer::max('id')+1;

                $customer->fill([
                    'company_id' => $currentUser->company_id,
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id,
                    'customer_code' => Formatter::code('c', $max_customer_id)
                ]);
            });

            static::updating(function($customer){
                $currentUser = Auth::user();

                $customer->fill([
                    'company_id' => $currentUser->company_id,
                    'updated_by' => $currentUser->id
                ]);
            });
        }        
        
    }



    public function sheet_productions(){
        return $this->hasMany('App\SheetProduction');
    }

    public function sales(){
        return $this->hasMany('App\Sale');
    }

    public function scopeCustomers($q){
        return $q->where('company_id', Auth::user()->id)->get();
    }
}
