<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Status extends Model
{
    public function purchases(){
        return $this->hasMany('App\Status');
    }

    public function sheet_productions(){
        return $this->hasMany('App\SheetProduction');
    }

    public function daily_productions(){
        return $this->hasMany('App\DailyProduction');
    }

    public function sales(){
        return $this->hasMany('App\Sale');
    }

    public function product_deliveries(){
        return $this->hasMany('App\ProductDeliveries');
    }

    public function temporary_sheet_productions(){
        return $this->hasMany('App\TemporarySheetProduction');
    }
}
