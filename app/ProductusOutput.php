<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductusOutput extends Model
{
    protected $fillable = [
        "warehouse", "id_client", "reissue","subtotal", "subtotal_with_discount","vat_total","discount_type", "discount_total", "rte_fuente", "rte_fuente_total", "total_invoice", "observations","id_traspase","created_at"
      ];
  
      protected $table         = 'product_output';
      public    $timestamps    = true;


      public function products(){
        return $this->hasMany('App\ProductusOutputItems', 'id_output')
                    ->join('products', 'products.id', '=', 'product_output_items.id_product')  
                    ->select(array('product_output_items.*','products.id as id_product', 'products.description', 'products.presentation', 'products.price_distributor_x_caja', 'products.price_distributor_x_vial', 'products.price_cliente_x_caja', 'products.price_cliente_x_vial'));
      }


}
