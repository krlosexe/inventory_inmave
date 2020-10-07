<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReemisionesItems extends Model
{
    protected $fillable = [
        "id_reemision", "id_product", "qty", "price", "vat", "total"
     ];
 
     protected $table  = 'reemisiones_items';
     public    $timestamps    = false; 
}
