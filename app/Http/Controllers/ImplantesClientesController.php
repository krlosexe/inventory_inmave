<?php

namespace App\Http\Controllers;

use App\ImplantesClientes;
use App\Auditoria;
use Illuminate\Http\Request;

class ImplantesClientesController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = ImplantesClientes::select("implantes_clients.*", "auditoria.*", "user_registro.email as email_regis")
                            ->join("auditoria", "auditoria.cod_reg", "=", "implantes_clients.id")
                            ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
                            ->where("auditoria.tabla", "implantes_clients")
                            ->where("auditoria.status", "!=", "0")
                            ->orderBy("implantes_clients.id", "DESC")
                            ->get();
        echo json_encode($clients);
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
        $clients = ImplantesClientes::create($request->all());
        $auditoria              = new Auditoria;
        $auditoria->tabla       = "implantes_clients";
        $auditoria->cod_reg     = $clients->id;
        $auditoria->status      = 1;
        $auditoria->fec_regins  = date("Y-m-d H:i:s");
        $auditoria->usr_regins  = $request["id_user"];
        $auditoria->save();

        if ($clients) {
            $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");    
            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json("A ocurrido un error")->setStatusCode(400);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\ImplantesClientes  $clients
     * @return \Illuminate\Http\Response
     */
    public function show(ImplantesClientes $clients)
    {
        $clients = ImplantesClientes::select("implantes_clients.*", "auditoria.*", "user_registro.email as email_regis")
                            ->join("auditoria", "auditoria.cod_reg", "=", "implantes_clients.id")
                            ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
                            ->where("auditoria.tabla", "implantes_clients")
                            ->where("auditoria.status", "!=", "0")
                            ->where("implantes_clients.id", $provider)
                            ->orderBy("implantes_clients.id", "DESC")
                            ->first();
        
        return response()->json($clients)->setStatusCode(200);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ImplantesClientes  $clients
     * @return \Illuminate\Http\Response
     */
    public function edit(ImplantesClientes $clients)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Clients  $clients
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $clients)
    {
        $update_clients = ImplantesClientes::find($clients)->update($request->all());
        if ($update_clients) {
            $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");    
            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json("A ocurrido un error")->setStatusCode(400);
        }
    }
    public function status($id, $status, Request $request)
    {
        $auditoria =  Auditoria::where("cod_reg", $id)->where("tabla", "implantes_clients")->first();
        $auditoria->status = $status;
        if($status == 0){
            $auditoria->usr_regmod = $request["id_user"];
            $auditoria->fec_regmod = date("Y-m-d");
        }
        $auditoria->save();
        $data = array('mensagge' => "Los datos fueron actualizados satisfactoriamente");    
        return response()->json($data)->setStatusCode(200);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ImplantesClientes  $clients
     * @return \Illuminate\Http\Response
     */
    public function destroy(ImplantesClientes $clients)
    {
        //
    }
}
