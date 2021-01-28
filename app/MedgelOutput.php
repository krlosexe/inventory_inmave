<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedgelOutput extends Model
{
    protected $table ="medgel_output";
    protected $guarded = [];
    public    $timestamps    = false;
    
    public function items(){
        return $this->hasMany(MedgelOutputItems::class, 'medgel_output_items');
    }
}
