<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    protected $fillable = [
        'responsable', 'issue', 'fecha', 'time', 'status_task', 'observaciones'
    ];

    protected $table         = 'tasks';
    public    $timestamps    = false;
    protected $primaryKey    = 'id_tasks';



}
