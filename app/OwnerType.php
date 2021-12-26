<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OwnerType extends Model
{
    protected $guarded = ['id'];

    public function chart_of_accounts(){
        return $this->hasMany('App\ChartOfAccount');
    }
}
