<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'fecha', 'title', 'id_user', 'id_event','type', 'view'
    ];

    protected $table         = 'notifications';
    public    $timestamps    = false;
    protected $primaryKey    = 'id_notifications';
}
