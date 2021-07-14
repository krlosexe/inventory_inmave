<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImplanteProductOutputItemTraspase extends Model
{
    protected $guarded =[];
    protected $table  = 'implante_product_output_items_trapase';
    public    $timestamps    = false; 


    public function product(){
        return $this->belongsTo(ProductImplantes::class,'id_product');
    }
}
