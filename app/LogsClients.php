<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogsClients extends Model
{
    protected $fillable = [
        'id_user', 'id_client', 'event'
    ];

    protected $table         = 'logs_client';
    public    $timestamps    = false;
    protected $primaryKey    = 'id';
}
