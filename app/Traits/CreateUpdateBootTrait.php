<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Auth;

trait CreateUpdateBootTrait{

    public static function boot(){

        parent::boot();

        $currentUser = Auth::user();

        static::creating(function($model){

            $model->company_id = $currentUser->company_id;
            $model->created_by = $currentUser->id;
            $model->updated_by = $currentUser->id;

        });

        static::updating(function($model){
            $model->company_id = $currentUser->company_id;
            $model->updated_by = $currentUser->id;
        });

        static::addGlobalScope('company_id', function(Builder $builder){
            $builder->where('company_id', $currentUser->company_id);
        });

    }

    public function test(){
        return 'test';
    }
    

}