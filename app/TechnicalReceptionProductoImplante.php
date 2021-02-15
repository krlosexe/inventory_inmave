<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TechnicalReceptionProductoImplante extends Model
{   
    protected $table="technical_reception_products_implante";
    protected $guarded=[];
    public    $timestamps    = false;

    public function products(){
      return $this->belongsTo(ProductImplantes::class, 'id_product');
    }
    public function head(){
      return $this->belongsTo(TechnicalReceptionImplante::class, 'id_technical_reception_implante');
    
    }
}
