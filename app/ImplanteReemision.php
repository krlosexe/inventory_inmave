<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImplanteReemision extends Model
{
    protected $table ="implantes_reemisiones";
    protected $guarded = [];
    
    public function items(){
        return $this->hasMany(ImplanteReemisionesItem::class, 'id_implante_reemision');
                    // ->join('products', 'products.id', '=', 'reemisiones_items.id_product')  
                    // ->select(array('reemisiones_items.*','products.id as id_product', 'products.description', 'products.presentation', 'products.price_distributor_x_caja', 'products.price_distributor_x_vial', 'products.price_cliente_x_caja', 'products.price_cliente_x_vial'));
      }
}
