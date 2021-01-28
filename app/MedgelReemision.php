<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedgelReemision extends Model
{
    protected $table ="medgel_reemisiones";
    protected $guarded = [];

    public function items(){
        return $this->hasMany(MedgelReemisionItems::class, 'id_medgel_reemision');
    
    }
}
