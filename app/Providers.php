<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Providers extends Model
{
    protected $fillable = [
        'name', 'nit', 'phone', 'email', 'address'
    ];

    protected $table         = 'providers';
    public    $timestamps    = false;
}
