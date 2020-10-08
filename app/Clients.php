<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    protected $fillable = [
        'name', 'nit', 'phone', 'email', 'address', 'city'
    ];

    protected $table         = 'clients';
    public    $timestamps    = false;
}
