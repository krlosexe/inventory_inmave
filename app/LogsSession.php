<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogsSession extends Model
{
    protected $fillable = [
        'id_user', 'date_login', 'date_logout'
    ];

    protected $table         = 'logs_sessions';
    public    $timestamps    = false;
    protected $primaryKey    = 'id';
}
