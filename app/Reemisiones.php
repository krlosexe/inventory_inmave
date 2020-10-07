<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reemisiones extends Model
{
    protected $fillable = [
        "warehouse", "id_client", "reissue","subtotal", "subtotal_with_discount","vat_total", "discount_total", "rte_fuente", "rte_fuente_total", "total_invoice", "observations", "created_at"
      ];
  
      protected $table         = 'reemisiones';
      public    $timestamps    = true;


      public function products(){
        return $this->hasMany('App\ReemisionesItems', 'id_reemision')
                    ->join('products', 'products.id', '=', 'reemisiones_items.id_product')  
                    ->select(array('reemisiones_items.*','products.id as id_product', 'products.description', 'products.presentation', 'products.price_distributor_x_caja', 'products.price_distributor_x_vial', 'products.price_cliente_x_caja', 'products.price_cliente_x_vial'));
      }
}
