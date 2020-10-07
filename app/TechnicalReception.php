<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TechnicalReception extends Model
{
    protected $fillable = [
        'id_provider', 'discount', 'rte_fuente', 'subtotal', 'vat_total', 'total_invoice', 'observations'
    ];

    protected $table         = 'technical_reception';
    public    $timestamps    = true;



    public function products()
    {
      return $this->hasMany('App\TechnicalReceptionProducts', 'id_technical_reception')
                    ->join('products', 'products.id', '=', 'technical_reception_products.id_product')  
                    ->select(array('technical_reception_products.*','products.id as id_product', 'products.description', 'products.commercial_presentation'));
    }



}
