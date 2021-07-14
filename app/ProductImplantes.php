<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductImplantes extends Model
{
    protected $table ="products_implantes";
    protected $guarded= [];

    public function user(){
        return $this->belongsTo(User::class,'id_user');
    }
}
