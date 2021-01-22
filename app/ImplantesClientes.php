<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImplantesClientes extends Model
{
    protected $guarded = ['id_user','token'];
    protected $table         = 'implantes_clients';
    public    $timestamps    = false;
}
