<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TechnicalReceptionProducts extends Model
{
    protected $fillable = [
        'id_technical_reception','id_product', 'laboratory', 'lote', 'register_invima', 'date_expiration', 'price', 'qty', 'vat', 'total'
    ];

    protected $table         = 'technical_reception_products';
    public    $timestamps    = false;
}
