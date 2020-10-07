<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductsEntryItems extends Model
{
    protected $fillable = [
        "id_entry", "id_product", "lote", "register_invima", "date_expiration", "qty", "price", "total"
     ];
 
     protected $table  = 'product_entry_items';
     public    $timestamps    = false;
}
