<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductsTrapase extends Model
{
    protected $table ="product_trapase";
    protected $guarded =[];
    public $timestamps = false;


      public function product(){
        return $this->belongsTo(Products::class,'id_product');
      }

}
