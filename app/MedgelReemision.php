<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedgelReemision extends Model
{
    protected $table ="medgel_reemisiones";
    protected $guarded = [];
    public    $timestamps    = false;

    public function items(){
        return $this->hasMany(MedgelReemisionItems::class, 'id_medgel_reemision');
    
    }
    public function client(){
        return $this->belongsTo(ImplantesClientes::class, 'id_client');
    
    }

    
}
