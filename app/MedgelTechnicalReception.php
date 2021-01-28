<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedgelTechnicalReception extends Model
{
    protected $table="medgel_technical_reception";
    protected $guarded=['token'];
    public    $timestamps    = true;

    public function detalle(){
        return $this->hasMany(MedgelTechnicalReceptionItem::class,'id_medgel_technical_reception');
    }

    public function proveedor(){
        return $this->belongsTo(Providers::class,'id_provider');
    }

    public function user(){
        return $this->belongsTo(User::class,'id_user');
    }
}
