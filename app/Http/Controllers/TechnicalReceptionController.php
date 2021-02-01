<?php

namespace App\Http\Controllers;

use App\Auditoria;
use App\TechnicalReception;
use App\TechnicalReceptionProducts;
use Illuminate\Http\Request;

class TechnicalReceptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = TechnicalReception::select("technical_reception.*", "auditoria.*", "user_registro.email as email_regis", "providers.name as name_provider")
                            ->join("auditoria", "auditoria.cod_reg", "=", "technical_reception.id")
                            ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
                            ->join("providers", "providers.id", "=", "technical_reception.id_provider")
                            ->where("auditoria.tabla", "technical_reception")
                            ->where("auditoria.status", "!=", "0")
                            ->with("products")
                            ->orderBy("technical_reception.id", "DESC")
                            ->get();
        return response()->json($data)->setStatusCode(200);
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
        $request["discount"]   =  str_replace(",", "", $request["discount"]);
        $request["rte_fuente"] =  str_replace(",", "", $request["rte_fuente"]);
        $technical_reception   = TechnicalReception::create($request->all());
        $auditoria              = new Auditoria;
        $auditoria->tabla       = "technical_reception";
        $auditoria->cod_reg     = $technical_reception->id;
        $auditoria->status      = 1;
        $auditoria->fec_regins  = date("Y-m-d H:i:s");
        $auditoria->usr_regins  = $request["id_user"];
        $auditoria->save();

        foreach($request["id_product"] as $key => $product){
            $products = [];
            $products["id_technical_reception"]  = $technical_reception->id;
            $products["id_product"]              = $product;
            $products["laboratory"]              = $request["laboratory"][$key];
            $products["lote"]                    = $request["lotes"][$key];
            $products["register_invima"]         = $request["register_invima"][$key];
            $products["date_expiration"]         = $request["date_expiration"][$key];
            $products["price"]                   = str_replace(",", "", $request["price"][$key]);
            $products["qty"]                     = $request["qty"][$key];
            $products["vat"]                     = $request["vat"][$key];
            $products["total"]                   = str_replace(",", "", $request["total"][$key]);

            TechnicalReceptionProducts::create($products);
        }
        if ($technical_reception) {
            $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");    
            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json("A ocurrido un error")->setStatusCode(400);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\TechnicalReception  $technicalReception
     * @return \Illuminate\Http\Response
     */
    public function show(TechnicalReception $technicalReception)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TechnicalReception  $technicalReception
     * @return \Illuminate\Http\Response
     */
    public function edit(TechnicalReception $technicalReception)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TechnicalReception  $technicalReception
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $technicalReception)
    {
        $request["discount"]   =  str_replace(",", "", $request["discount"]);
        $request["rte_fuente"] =  str_replace(",", "", $request["rte_fuente"]);
        $update = TechnicalReception::find($technicalReception)->update($request->all());
        TechnicalReceptionProducts::where("id_technical_reception", $technicalReception)->delete();

        foreach($request["id_product"] as $key => $product){
            $products = [];
            $products["id_technical_reception"]  = $technicalReception;
            $products["id_product"]              = $product;
            $products["laboratory"]              = $request["laboratory"][$key];
            $products["lote"]                    = $request["lotes"][$key];
            $products["register_invima"]         = $request["register_invima"][$key];
            $products["date_expiration"]         = $request["date_expiration"][$key];
            $products["price"]                   = str_replace(",", "", $request["price"][$key]);
            $products["qty"]                     = $request["qty"][$key];
            $products["vat"]                     = $request["vat"][$key];
            $products["total"]                   = str_replace(",", "", $request["total"][$key]);

            TechnicalReceptionProducts::create($products);
        }
        if ($update) {
            $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");    
            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json("A ocurrido un error")->setStatusCode(400);
        }
    }
    public function status($id, $status, Request $request)
    {
        $auditoria =  Auditoria::where("cod_reg", $id)->where("tabla", "technical_reception")->first();
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
     * @param  \App\TechnicalReception  $technicalReception
     * @return \Illuminate\Http\Response
     */
    public function destroy(TechnicalReception $technicalReception)
    {
        //
    }
}
