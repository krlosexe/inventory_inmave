<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImplanteReemision extends Model
{
    protected $table ="implantes_reemisiones";
    protected $guarded = [];
    
    public function items(){
        return $this->hasMany(ImplanteReemisionesItem::class, 'id_implante_reemision')
        ->join('products_implantes', 'products_implantes.referencia', '=', 'implantes_reemisiones_items.referencia')
        ->join("technical_reception_products_implante", "implantes_reemisiones_items.serial","technical_reception_products_implante.serial")
        ->groupBY("implantes_reemisiones_items.serial")  
        ->select(array('implantes_reemisiones_items.*','products_implantes.id as id_product', 'products_implantes.description','technical_reception_products_implante.estatus'));
      }
}
