<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImplanteProductOutputTraspase extends Model
{
    protected $table ="implante_product_output_traspase";
    protected $guarded =[];
    public $timestamps = false;


    // public function details_traspase(){
    //     return $this->hasMany(ProductusOutputItemsTraspase::class,'id_output_traspase');
    // }

    public function usuario(){
        return $this->belongsTo(User::class,'id_user');
    }
}
