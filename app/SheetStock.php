<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class SheetStock extends Model
{
    protected $guarded = ['id'];


    public static function boot(){
        parent::boot();

        static::creating(function($sheet_stock){
            $currentUser = Auth::user();

            $sheet_stock->fill([
                'company_id' => $currentUser->company_id,
                'created_by' => $currentUser->id,
                'updated_by' => $currentUser->id
            ]);
        });

        static::updating(function($sheet_stock){
            $currentUser = Auth::user();

            $sheet_stock->fill([
                'company_id' => $currentUser->company_id,
                'created_by' => $currentUser->id,
                'updated_by' => $currentUser->id
            ]);

        });
    }

    public function sheet_size(){
        return $this->belongsTo('App\SheetSize');
    }

    public function color(){
        return $this->belongsTo('App\Color');
    }

    public function sub_raw_material(){
        return $this->belongsTo('App\SubRawMaterial');
    }

}
