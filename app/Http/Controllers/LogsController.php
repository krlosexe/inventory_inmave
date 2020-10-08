<?php

namespace App\Http\Controllers;

use App\LogsSession;
use App\ClientsTasks;
use App\Valuations;
use App\Preanesthesia;
use App\Surgeries;
use App\RevisionAppointment;
use DB;
use Illuminate\Http\Request;

class LogsController extends Controller
{
    public function session(Request $request){

        $adviser = 0;
        if(isset($request["adviser"])){
            $adviser = $request["adviser"];
        }

        $data = LogsSession::select("logs_sessions.*", "datos_personales.nombres as name_responsable", 
                                    "datos_personales.apellido_p as last_name_responsable")

                            ->join("datos_personales", "datos_personales.id_usuario", "=", "logs_sessions.id_user")

                            ->where(function ($query) use ($adviser) {
                                if($adviser != 0){
                                    $query->where("logs_sessions.id_user", $adviser);
                                }
                            })

                            ->orderBy("logs_sessions.id", "DESC")
                            ->get();

        return response()->json($data)->setStatusCode(200);
    }



    public function EventsAdvisers(Request $request){

        $adviser = 0;
        if(isset($request["adviser"])){
            $adviser = $request["adviser"];
        }

        $data = [];
        $tasks          = $this->getTaskClients($adviser);
        $valorations    = $this->getValuations($adviser);
        $preanesthesias = $this->Preanesthesia($adviser);
        $surgeries      = $this->Surgeries($adviser);
        $revision       = $this->Revision($adviser);

        foreach($tasks as  $value){
            $data[] = $value;
        }

        foreach($valorations as  $value){
            $data[] = $value;
        }


        foreach($preanesthesias as  $value){
            $data[] = $value;
        }


        foreach($surgeries as  $value){
            $data[] = $value;
        }


        foreach($revision as  $value){
            $data[] = $value;
        }



        return $data;
    }





    function Revision($adviser){

        $data = RevisionAppointment::select("revision_appointment.id_revision", "appointments_agenda.fecha as start", "appointments_agenda.time as time","appointments_agenda.time_end as time_end",
                                            "appointments_agenda.descripcion as observaciones", "clientes.nombres as name_client", "clientes.apellidos as last_name_client", "users.img_profile", 
                                            "datos_personales.nombres", "datos_personales.apellido_p", "clinic.nombre as name_clinic", "auditoria.fec_regins"
                                           )

                                    ->join("auditoria", "auditoria.cod_reg", "=", "revision_appointment.id_revision")
                                    ->join("appointments_agenda", "appointments_agenda.id_revision", "=", "revision_appointment.id_revision")
                                    ->join("clientes", "clientes.id_cliente", "=", "revision_appointment.id_paciente")
                                    ->join("clinic", "clinic.id_clinic", "=", "revision_appointment.clinica", "left")
                                    ->join("users", "users.id", "=", "auditoria.usr_regins")
                                    ->join("datos_personales", "datos_personales.id_usuario", "=", "auditoria.usr_regins")


                                    ->where(function ($query) use ($adviser) {
                                        if($adviser != 0){
                                            $query->whereIn("auditoria.usr_regins", $adviser);
                                        }
                                    }) 

                                    ->where("auditoria.tabla", "revision_appointment")
                                    ->where("auditoria.status", "!=", 0)
                                    ->get();

                                 
        foreach($data as $key => $value){

            $value["event"] = "Revision: ".$value["name_client"]." ".$value["last_name_client"];
            $value["state"] = "";
            
        }

        return $data;
    }





    function Surgeries($adviser){
        

        $data = Surgeries::select("surgeries.id_surgeries","surgeries.fecha as start", "surgeries.time as time", "surgeries.time_end as time_end",
                                   "surgeries.observaciones", "surgeries.attempt", "surgeries.type", "surgeries.status_surgeries as state" ,"clientes.nombres as name_client", "clientes.apellidos as last_name_client", "users.img_profile", 
                                   "datos_personales.nombres", "datos_personales.apellido_p", "clinic.nombre as name_clinic", "auditoria.usr_regins", "auditoria.fec_regins")

                                ->join("auditoria", "auditoria.cod_reg", "=", "surgeries.id_surgeries")
                                ->join("clientes", "clientes.id_cliente", "=", "surgeries.id_cliente")
                                ->join("clinic", "clinic.id_clinic", "=", "surgeries.clinic", "left")
                                ->join("users", "users.id", "=", "auditoria.usr_regins")
                                ->join("datos_personales", "datos_personales.id_usuario", "=", "auditoria.usr_regins")


                                ->where(function ($query) use ($adviser) {
                                    if($adviser != 0){
                                        $query->whereIn("auditoria.usr_regins", $adviser);
                                    }
                                }) 


                                ->where("surgeries.status_surgeries", 0)
                                ->where("auditoria.tabla", "surgeries")
                                ->where("auditoria.status", "!=", 0)
                            
                                ->get();

        foreach($data as $key => $value){

            $type   = $value["type"] == "Financiado" ? "FX " : "";
            $prefix = $value["attempt"] == 1 ? "FT: " : "CX: ";

            $value["event"] =  $prefix.$type.$value["name_client"]." ".$value["last_name_client"];

           
        }
        return $data;
    }







    function Preanesthesia($adviser){


        $data = Preanesthesia::select("preanesthesias.id_preanesthesias","preanesthesias.fecha as start", "preanesthesias.time as time",  "preanesthesias.time_end as time_end",
                                   "preanesthesias.observaciones", "preanesthesias.status_surgeries as state", "clientes.nombres as name_client", "clientes.apellidos as last_name_client", "users.img_profile", 
                                   "datos_personales.nombres", "datos_personales.apellido_p", "clinic.nombre as name_clinic", "auditoria.usr_regins", "auditoria.fec_regins")

                                    ->join("auditoria", "auditoria.cod_reg", "=", "preanesthesias.id_preanesthesias")
                                    ->join("clientes", "clientes.id_cliente", "=", "preanesthesias.id_cliente")
                                    ->join("users", "users.id", "=", "auditoria.usr_regins")
                                    ->join("clinic", "clinic.id_clinic", "=", "preanesthesias.clinic", "left")
                                    ->join("datos_personales", "datos_personales.id_usuario", "=", "auditoria.usr_regins")


                                    ->where(function ($query) use ($adviser) {
                                        if($adviser != 0){
                                            $query->whereIn("auditoria.usr_regins", $adviser);
                                        }
                                    }) 

                                    ->where("preanesthesias.status_surgeries", 0)
                                    ->where("auditoria.tabla", "preanesthesias")
                                    ->where("auditoria.status", "!=", 0)
                                
                                    ->get();


        foreach($data as $key => $value){
            $prefix = "Pre Antestesia: ";
            $value["event"] =  $prefix.$value["name_client"]." ".$value["last_name_client"];
        }
        return $data;
    }







    function getValuations($adviser){
        
       // echo json_encode($adviser);

        $data = Valuations::select("valuations.id_valuations","valuations.fecha as start", "valuations.time as time", "valuations.time_end as time_end",
                                   "valuations.observaciones", "valuations.status as state","clientes.nombres as name_client", "clientes.apellidos as last_name_client", "users.img_profile", 
                                   "datos_personales.nombres", "datos_personales.apellido_p", "clinic.nombre as name_clinic",
                                   "auditoria.usr_regins", "auditoria.fec_regins"
                                )


                                ->join("auditoria", "auditoria.cod_reg", "=", "valuations.id_valuations")

                                ->join("clientes", "clientes.id_cliente", "=", "valuations.id_cliente")
                                ->join("clinic", "clinic.id_clinic", "=", "valuations.clinic", "left")
                                ->join("users", "users.id", "=", "auditoria.usr_regins")
                                ->join("datos_personales", "datos_personales.id_usuario", "=", "auditoria.usr_regins")
                               

                                ->where(function ($query) use ($adviser) {
                                    if($adviser != 0){
                                        $query->whereIn("auditoria.usr_regins", $adviser);
                                    }
                                }) 

                                
                                ->where("auditoria.tabla", "valuations")
                                ->where("valuations.status", 0)
                                ->where("auditoria.status", "!=", 0)
                            
                                ->get();

        $array = [];
        foreach($data as $key => $value){

            $value["event"] = "VLR: ".$value["name_client"]." ".$value["last_name_client"];
            $array[] = $value;
        }
        return $array;

        
    }





    function getTaskClients($adviser){
        
        
        $data = ClientsTasks::select("clients_tasks.id_clients_tasks", "clients_tasks.issue as title", 
                                     "clients_tasks.fecha as start", "clients_tasks.time as time", "clients_tasks.status_task as state","datos_personales.nombres",
                                     "datos_personales.apellido_p", "user_responsable.img_profile",
                                     "clientes.nombres as name_client", "clientes.apellidos as last_name_client", "clients_tasks.responsable", "auditoria.fec_regins"
                                    )

                            ->join("clientes", "clientes.id_cliente", "=", "clients_tasks.id_client", "left")
                            ->join("datos_personales", "datos_personales.id_usuario", "=", "clients_tasks.responsable", "left")
                            ->join("users as user_responsable", "user_responsable.id", "=", "clients_tasks.responsable", "left")

                         

                            ->where(function ($query) use ($adviser) {
                                
                                if($adviser != 0){
                                    $query->whereIn("clients_tasks.responsable", $adviser);
                                }
                            }) 


                            ->join("auditoria", "auditoria.cod_reg", "=", "clients_tasks.id_clients_tasks")
                            ->where("auditoria.tabla", "clients_tasks")


                            ->where("auditoria.status", "!=", 0)

                            ->orderBy("clients_tasks.id_clients_tasks", "asc")
                            
                            ->get();



        foreach($data as $key => $value){
            $value["event"]  = "Tareas";
           
        }
        return $data;
    }



    public function eventsClients(Request $request){

        if($request["adviser"] != null){
            $adviser = $request["adviser"];
        }else{
            $adviser = 0;
        }
       $data = DB::table("logs_client")
                ->selectRaw("logs_client.*, clientes.nombres, CONCAT(datos_personales.nombres, ' ', datos_personales.apellido_p) as name_asesora")
                ->join("clientes", "clientes.id_cliente", "=", "logs_client.id_client")
                ->join("users", "users.id", "=", "logs_client.id_user")
                ->join("datos_personales", "datos_personales.id_usuario", "=", "users.id")

                ->where(function ($query) use ($adviser) {
                                
                    if($adviser != 0){
                        $query->whereIn("logs_client.id_user", $adviser);
                    }
                }) 
    
                ->orderBy("logs_client.create_at", "DESC")
                ->get();


       return response()->json($data)->setStatusCode(200);



    }




}
