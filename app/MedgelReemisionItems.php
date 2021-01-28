<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedgelReemisionItems extends Model
{
    protected $table ="medgel_reemisiones_items";
    protected $guarded = [];
    public    $timestamps    = false;

    public function product(){
        return $this->belongsTo(MedgelProduct::class,'id');
    
    }
}
