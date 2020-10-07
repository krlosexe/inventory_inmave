<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersLine extends Model
{
    protected $fillable = [
        'id_user', 'id_line'
    ];

    protected $table         = 'users_line_business';
    public    $timestamps    = false;
    protected $primaryKey    = 'id_users_line_business';

}
