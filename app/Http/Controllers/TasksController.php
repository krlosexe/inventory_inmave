<?php

namespace App\Http\Controllers;

use App\Tasks;
use App\Auditoria;
use App\TasksFollowers;



use Illuminate\Http\Request;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($this->VerifyLogin($request["id_user"],$request["token"])){

            $rol     = $request["rol"];
            $id_user = $request["id_user"];


            $adviser = 0;
            if(isset($request["adviser"])){
              $adviser = $request["adviser"];
            }

            $tasks = Tasks::select("tasks.*", "responsable.email as email_responsable", "datos_personales.nombres as name_responsable", 
                                   "datos_personales.apellido_p as last_name_responsable", "auditoria.*", "users.email as email_regis")

                                ->join("auditoria", "auditoria.cod_reg", "=", "tasks.id_tasks")
                                ->join("users", "users.id", "=", "auditoria.usr_regins")

                                ->join("users as responsable", "responsable.id", "=", "tasks.responsable")
                                ->join("datos_personales", "datos_personales.id_usuario", "=", "responsable.id")


                                ->where("auditoria.tabla", "tasks")
                                ->where("auditoria.status", "!=", "0")
                                ->orderBy("tasks.id_tasks", "DESC")
                                ->get();



            if($rol == "Asesor"){

                $tasks_follow = Tasks::select("tasks.*", "responsable.email as email_responsable", "datos_personales.nombres as name_responsable", 
                                                    "datos_personales.apellido_p as last_name_responsable", "auditoria.*", "users.email as email_regis")

                                                    ->join("auditoria", "auditoria.cod_reg", "=", "tasks.id_tasks")
                                                    ->join("users", "users.id", "=", "auditoria.usr_regins")

                                                    ->join("users as responsable", "responsable.id", "=", "tasks.responsable")
                                                    ->join("datos_personales", "datos_personales.id_usuario", "=", "responsable.id")

                                                    ->join("tasks_followers", "tasks_followers.id_task", "=", "tasks.id_tasks")

                                                    ->with("followers")
                                                    
                                                    ->where("tasks_followers.id_follower", $id_user)
                                                    ->where("auditoria.tabla", "tasks")
                                                    ->where("auditoria.status", "!=", "0")
                                                    ->orderBy("tasks.id_tasks", "DESC")
                                                    ->get();


                foreach($tasks_follow as $key => $value){
                  $tasks[] = $value;
                }
            }
            
                             
            echo json_encode($tasks);

        }else{
            return response()->json("No esta autorizado")->setStatusCode(400);
        }
    }





    



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($this->VerifyLogin($request["id_user"],$request["token"])){


            $store = Tasks::create($request->all());


            $auditoria              = new Auditoria;
            $auditoria->tabla       = "tasks";
            $auditoria->cod_reg     = $store["id_tasks"];
            $auditoria->status      = 1;
            $auditoria->fec_regins  = date("Y-m-d H:i:s");
            $auditoria->usr_regins  = $request["id_user"];
            $auditoria->save();

            if ($store) {
                $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");    
                return response()->json($data)->setStatusCode(200);
            }else{
                return response()->json("A ocurrido un error")->setStatusCode(400);
            }
     
        }else{
            return response()->json("No esta autorizado")->setStatusCode(400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function show(Tasks $tasks)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function edit(Tasks $tasks)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $tasks)
    {
          
            $update = Tasks::find($tasks)->update($request->all());

            if ($update) {
                $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");    
                return response()->json($data)->setStatusCode(200);
            }else{
                return response()->json("A ocurrido un error")->setStatusCode(400);
            }

        
    }


    public function status($id, $status)
    {
        
        $auditoria =  Auditoria::where("cod_reg", $id)
                                    ->where("tabla", "tasks")->first();

                                  
        $auditoria->status = $status;

        if($status == 0){
            $auditoria->usr_regmod = 60;
            $auditoria->fec_regmod = date("Y-m-d");
        }
        $auditoria->save();

        $data = array('mensagge' => "Los datos fueron actualizados satisfactoriamente");    
        return response()->json($data)->setStatusCode(200);
        
    }



    public function Migrate(){
       
        

        $tasks = Tasks::select("tasks.*", "responsable.email as email_responsable", "datos_personales.nombres as name_responsable", 
                                   "datos_personales.apellido_p as last_name_responsable", "auditoria.*", "users.email as email_regis", "auditoria.usr_regins")

                                ->join("auditoria", "auditoria.cod_reg", "=", "tasks.id_tasks")
                                ->join("users", "users.id", "=", "auditoria.usr_regins")

                                ->join("users as responsable", "responsable.id", "=", "tasks.responsable")
                                ->join("datos_personales", "datos_personales.id_usuario", "=", "responsable.id")

                                ->with("followers")


                                ->where("auditoria.tabla", "tasks")
                                ->where("auditoria.status", "!=", "0")
                                ->orderBy("tasks.id_tasks", "DESC")
                                ->get();

        
        foreach($tasks as $value){
            echo json_encode($value["observaciones"])."<br><br>";

            $data = [];
            $data["responsable"] = $value["responsable"];
            $data["issue"]       = $value["issue"]; 
            $data["fecha"]       = $value["fecha"];
            $data["time"]        = $value["time"];
            $data["status_task"] = $value["status_task"]; 
            $store = Tasks::create($data);


            $auditoria              = new Auditoria;
            $auditoria->tabla       = "tasks";
            $auditoria->cod_reg     = $store["id_tasks"];
            $auditoria->status      = 1;
            $auditoria->usr_regins  = $value["usr_regins"];
            $auditoria->save();


            $followers = [];
            foreach($value["followers"] as $key => $follow){
                $array = [];
                $array["id_task"]     = $store["id_tasks"];
                $array["id_follower"] = $follow["id_follower"];
                array_push($followers, $array);
            }
            TasksFollowers::insert($followers);

            $comments = [];
            $comments["id_task"]   = $store["id_tasks"];
            $comments["id_user"]   = $value["usr_regins"];
            $comments["comments"]  = $value["observaciones"];
            TasksComments::create($comments);


        }

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tasks $tasks)
    {
        //
    }
}
