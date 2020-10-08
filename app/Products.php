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
}
