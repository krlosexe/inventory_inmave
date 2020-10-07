<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;


use App\Valuations;
use App\Notification;
use App\User;

use Mail;

class NotificationsValorations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registered:NotificationsValorations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recordad valoracione vencidas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        $this->line("Some text");
        $this->info("Hey, watch this !");
        $this->comment("Just a comment passing by");

        //php /var/www/html/crm/artisan registered:Logouts
        $data = Valuations::select("valuations.*", "clientes.nombres", "auditoria.usr_regins")
                    ->join("auditoria", "auditoria.cod_reg", "=", "valuations.id_valuations")
                    ->join("clientes", "clientes.id_cliente", "=", "valuations.id_cliente")
                    ->where("valuations.fecha", "<", date("Y-m-d"))
                    ->where("valuations.status", 0)
                    ->where("auditoria.tabla", "valuations")
                    ->where("auditoria.status", 1)
                    ->get();

        foreach($data as $event){

            $this->comment("Just a comment passing by");

            $notification             = [];
            $notification["fecha"]    = $event->fecha;
            $notification["title"]    = "Valoracion ".$event->code." esta vencida PX: ".$event->nombres;
            $notification["id_user"]  = $event->usr_regins;
            $notification["id_event"] = $event->id_valuations;
            $notification["type"]     = "valuations";

            Notification::insert($notification);


            $info_email = [
                "user_id" => $event->usr_regins,
                "issue"   => $notification["title"],
                "mensage" => $notification["title"],
            ];
            $this->SendEmail($info_email);

        }


    }


    public function SendEmail($data){

        $user = User::find($data["user_id"]);
        $subject = $data["issue"];

        //$for = "cardenascarlos18@gmail.com";
        $for = $user["email"];

        $request["msg"] = $data["mensage"];

        Mail::send('emails.notification',$request, function($msj) use($subject,$for){
            $msj->from("cardenascarlos18@gmail.com","CRM");
            $msj->subject($subject);
            $msj->to($for);
        });

        return true;

    }
}
