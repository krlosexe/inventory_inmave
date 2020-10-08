<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Mail;

use App\ClientsTasks;
use App\Notification;
use App\User;

use App\Valuations;


class JobsController extends Controller
{
    public function Logouts(){
        echo "adasd";
    }

    public function TasksOverdue(){

        $data = ClientsTasks::where("fecha", "<", date("Y-m-d"))
                    ->where("status_task", "Abierta")
                    ->get();

        foreach($data as $task){
            $notification             = [];
            $notification["fecha"]    = $task->fecha;
            $notification["title"]    = "Tarea ".$task->id_clients_tasks." esta vencida: ".$task->issue;
            $notification["id_user"]  = $task->responsable;
            $notification["id_event"] = $task->id_clients_tasks;
            $notification["type"]     = "task";

            Notification::insert($notification);


            $info_email = [
                "user_id" => $task->responsable,
                "issue"   => $notification["title"],
                "mensage" => $notification["title"],
            ];
            $this->SendEmail($info_email);

        }

        return response()->json($data)->setStatusCode(200);
        
    }







    public function ValuationsOverdue(){

        $data = Valuations::select("valuations.*", "clientes.nombres", "auditoria.usr_regins")
                    ->join("auditoria", "auditoria.cod_reg", "=", "valuations.id_valuations")
                    ->join("clientes", "clientes.id_cliente", "=", "valuations.id_cliente")
                    ->where("valuations.fecha", "<", date("Y-m-d"))
                    ->where("valuations.status", 0)
                    ->where("auditoria.tabla", "valuations")
                    ->where("auditoria.status", 1)
                    ->get();

        foreach($data as $event){
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

        return response()->json($data)->setStatusCode(200);
        
    }







    public function SendEmail($data){

        $user = User::find($data["user_id"]);
        $subject = $data["issue"];

        $for = "cardenascarlos18@gmail.com";
        //$for = $user["email"];

        $request["msg"] = $data["mensage"];

        Mail::send('emails.notification',$request, function($msj) use($subject,$for){
            $msj->from("cardenascarlos18@gmail.com","CRM");
            $msj->subject($subject);
            $msj->to($for);
        });

        return true;

    }



}
