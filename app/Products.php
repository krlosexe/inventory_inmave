<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = [
        'code', 'description', 'register_invima', 'price_euro', 'price_cop', 'presentation', 'price_distributor_x_caja', 'price_distributor_x_vial', 'price_cliente_x_caja', 'price_cliente_x_vial'
    ];

    protected $table         = 'products';
    public    $timestamps    = false;

    public function total_productos(){
        return $this->hasMany(ProductsEntryItems::class,'id_product');
    }

    public function total_productos_salida(){
        return $this->hasMany(ProductusOutputItems::class,'id_product');
    }

}
