<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfigMaterial extends Model
{
    protected $guarded = ['id'];

    public function raw_material(){
        return $this->belongsTo('App\RawMaterial');
    }

    public function sheet_production_materials(){
        return $this->where('name', 'App\SheetProduction')->pluck('raw_material_id');
    }
}
