<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedgelTechnicalReceptionItem extends Model
{
    protected $table="medgel_technical_reception_items";
    protected $guarded = [];
    public    $timestamps = false;

    public function product(){
        return $this->belongsTo(MedgelProduct::class,'id_product');
    }
}
