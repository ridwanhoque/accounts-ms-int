<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChartType extends Model
{
    protected $guarded = ['id'];

    public function chart_of_acconts(){
        return $this->hasMany('App\ChartOfAccount');
    }
}
