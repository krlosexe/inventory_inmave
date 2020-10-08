<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class NotificationApp extends Controller
{
    public function index(){

      $ConfigNotification = [
        "tokens" => [
            "fem_jxiMamA:APA91bFez7UPh6XCPLjmGIuYx4myvxjLHs-PEL80Ct615XMMA7oOtyWMKDSIAX7G7I831_DJMXoAgbzCJ0W0Vz8e3acs_jns4une2f3sCyL4pwQjQ3oATw46rYJZEiNnkKw6uQf_8ZwV"
        ],

        "tittle" => "Para Leo, de Camila",
        "body"   => "Nesecito Verte, Respondeme",
        "data"   => ['type' => 'refferers']

      ];

      $code = SendNotifications($ConfigNotification);

      echo "aassa";

    }
}