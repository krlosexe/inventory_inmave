<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImplantOutput extends Model
{
    protected $guarded = [];
  
      protected $table         = 'implantes_output';
      public    $timestamps    = true;

      public function items(){
        return $this->hasMany(ImplantOutputItems::class,'id_implant_output')
                    ->join('products_implantes','implantes_output_items.id_product', 'products_implantes.id')  
                    ->select(array('implantes_output_items.*','products_implantes.id as id_product', 'products_implantes.description'));
      }
}
