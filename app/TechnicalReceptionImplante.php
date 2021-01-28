<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TechnicalReceptionImplante extends Model
{
    protected $table="technical_reception_implante";
    protected $guarded = [];
    public    $timestamps    = true;

    // public function detalle(){
    //     return $this->hasMany(TechnicalReceptionProductoImplante::class,'id_technical_reception_implante');
                                   
    // }

    public function proveedor(){
        return $this->belongsTo(Providers::class,'id_provider');
    }

    public function user(){
        return $this->belongsTo(User::class,'id_user');
    }
}
