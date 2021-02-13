<?php

namespace App\Http\Controllers;

use App\Providers;
use App\Auditoria;
use Illuminate\Http\Request;

class ProvidersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        $providers = Providers::select("providers.*", "auditoria.*", "user_registro.email as email_regis")
                            ->join("auditoria", "auditoria.cod_reg", "=", "providers.id")
                            ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
                            ->where("auditoria.tabla", "providers")
                            ->where("auditoria.status", "!=", "0")
                            ->orderBy("providers.id", "DESC")
                            ->get();
        echo json_encode($providers);
        
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
        $providers = Providers::create($request->all());

        $auditoria              = new Auditoria;
        $auditoria->tabla       = "providers";
        $auditoria->cod_reg     = $providers->id;
        $auditoria->status      = 1;
        $auditoria->fec_regins  = date("Y-m-d H:i:s");
        $auditoria->usr_regins  = $request["id_user"];
        $auditoria->save();

        if ($providers) {
            $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");    
            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json("A ocurrido un error")->setStatusCode(400);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Providers  $providers
     * @return \Illuminate\Http\Response
     */
    public function show($provider)
    {
        // dd($provider);
        $providers = Providers::select("providers.*", "auditoria.*", "user_registro.email as email_regis")
                            ->join("auditoria", "auditoria.cod_reg", "=", "providers.id")
                            ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
                            ->where("auditoria.tabla", "providers")
                            ->where("auditoria.status", "!=", "0")
                            ->where("providers.id", $provider)
                            ->orderBy("providers.id", "DESC")
                            ->first();
        
        return response()->json($providers)->setStatusCode(200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Providers  $providers
     * @return \Illuminate\Http\Response
     */
    public function edit($provider)
    {
       

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Providers  $providers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $provider)
    {
        $update_provider = Providers::find($provider)->update($request->all());

        if ($update_provider) {
            $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");    
            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json("A ocurrido un error")->setStatusCode(400);
        }
    }



    public function status($id, $status, Request $request)
    {
      
        $auditoria =  Auditoria::where("cod_reg", $id)
                                    ->where("tabla", "providers")->first();

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
     * @param  \App\Providers  $providers
     * @return \Illuminate\Http\Response
     */
    public function destroy(Providers $providers)
    {
        //
    }
}
