<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedgelProduct extends Model
{
    protected $table="medgel_products";
    protected $guarded = ['_token'];

    public function user(){
        return $this->belongsTo(User::class,'id_user');
    }

}
