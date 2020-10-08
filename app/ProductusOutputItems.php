<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductusOutputItems extends Model
{
    protected $fillable = [
        "id_output", "id_product", "qty", "price", "vat", "total"
     ];
 
     protected $table  = 'product_output_items';
     public    $timestamps    = false; 
}
