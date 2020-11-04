<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductusOutputItemsTraspase extends Model
{
    protected $guarded =[];
    protected $table  = 'product_output_items_trapase';
    public    $timestamps    = false; 


    public function product(){
        return $this->belongsTo(Products::class,'id_product');
    }

}
