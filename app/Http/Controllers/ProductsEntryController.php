<?php

namespace App\Http\Controllers;

use App\Auditoria;
use App\ProductsEntry;
use App\ProductsEntryItems;
use Illuminate\Http\Request;

class ProductsEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ProductsEntry::select("products_entry.*", "auditoria.*", "user_registro.email as email_regis")
                                ->join("auditoria", "auditoria.cod_reg", "=", "products_entry.id")
                                ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
                                ->where("auditoria.tabla", "products_entry")
                                ->where("auditoria.status", "!=", "0")
                                ->orderBy("products_entry.id", "DESC")


                                ->with("products")
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
        $entry = ProductsEntry::create($request->all());

        $auditoria              = new Auditoria;
        $auditoria->tabla       = "products_entry";
        $auditoria->cod_reg     = $entry->id;
        $auditoria->status      = 1;
        $auditoria->fec_regins  = date("Y-m-d H:i:s");
        $auditoria->usr_regins  = $request["id_user"];
        $auditoria->save();


        if(isset($request["id_product"])){
            foreach($request["id_product"] as $key => $value){
                $producs_items["id_entry"]          = $entry->id;
                $producs_items["id_product"]        = $value;
                $producs_items["lote"]              = $request["lotes"][$key];
                $producs_items["register_invima"]   = $request["register_invima"][$key];
                $producs_items["date_expiration"]   = $request["date_expiration"][$key];
                $producs_items["qty"]               = $request["qty"][$key];
                $producs_items["price"]             = $request["price"][$key];
                $producs_items["total"]             = $request["total"][$key];
                ProductsEntryItems::create($producs_items);
            }   
        }
        

        if ($entry) {
            $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");    
            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json("A ocurrido un error")->setStatusCode(400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductsEntry  $productsEntry
     * @return \Illuminate\Http\Response
     */
    public function show(ProductsEntry $productsEntry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductsEntry  $productsEntry
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductsEntry $productsEntry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductsEntry  $productsEntry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $productsEntry)
    {


       

        $update = ProductsEntry::find($productsEntry)->update($request->all());


        ProductsEntryItems::where("id_entry", $productsEntry)->delete();

        if(isset($request["id_product"])){
            foreach($request["id_product"] as $key => $value){

                $producs_items["id_entry"]          = $productsEntry;
                $producs_items["id_product"]        = $value;
                $producs_items["lote"]              = $request["lotes"][$key];
                $producs_items["register_invima"]   = $request["register_invima"][$key];
                $producs_items["date_expiration"]   = $request["date_expiration"][$key];
                $producs_items["qty"]               = $request["qty"][$key];
                $producs_items["price"]             = str_replace(",", "", $request["price"][$key]);
                $producs_items["total"]             = str_replace(",", "", $request["total"][$key]);
                ProductsEntryItems::create($producs_items);
                
            }   
        }


        if ($update) {
            $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");    
            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json("A ocurrido un error")->setStatusCode(400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductsEntry  $productsEntry
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductsEntry $productsEntry)
    {
        //
    }
}
