<?php

namespace App\Http\Controllers;

use DB;
use App\Auditoria;
use App\ProductusOutput;
use App\ProductusOutputItems;
use Illuminate\Http\Request;

class ProductusOutputController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ProductusOutput::select("product_output.*", "clients.name as name_client","auditoria.*", "user_registro.email as email_regis")
                                ->join("auditoria", "auditoria.cod_reg", "=", "product_output.id")
                                ->join("clients", "clients.id", "=", "product_output.id_client")
                                ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
                                ->where("auditoria.tabla", "products_output")
                                ->where("auditoria.status", "!=", "0")
                                ->orderBy("product_output.id", "DESC")
                                ->with("products")
                                ->get();

        $total = 0;
        foreach($data as $value){
            foreach($value["products"] as $product){
                $entry_medellin = DB::table("product_entry_items")
                            ->selectRaw("product_entry_items.id_product, products.description, (SUM(product_entry_items.qty))  as total")
                            ->join("products_entry", "products_entry.id", "product_entry_items.id_entry")
                            ->join("products", "products.id", "product_entry_items.id_product")
                            ->where("products_entry.warehouse", "Medellin")
                            ->where("products.id", $product["id_product"])
                            ->groupBy("product_entry_items.id_product")
                            ->first();
                $output_medellin = DB::table("product_output_items")
                                    ->selectRaw("product_output_items.id_product, products.description, (SUM(product_output_items.qty))  as total")
                                    ->join("product_output", "product_output.id", "product_output_items.id_output")
                                    ->join("products", "products.id", "product_output_items.id_product")
                                    ->where("product_output.warehouse", "Medellin")
                                    ->where("products.id", $product["id_product"])
                                    ->groupBy("product_output_items.id_product")
                                    ->first();               
                $total = 0;
                if($entry_medellin){
                    $total_output_medellin = 0;
                    if($output_medellin){
                        $total_output_medellin = $output_medellin->total;
                    }
                    $total = $entry_medellin->total - $total_output_medellin;
                }else{
                    $total = 0;
                }
                $product["existence"] = $total;
            }
        }
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
        isset($request["reissue"])  ? $request["reissue"] = 1 : $request["reissue"] = 0;

        $output = ProductusOutput::create($request->all());
        $auditoria              = new Auditoria;
        $auditoria->tabla       = "products_output";
        $auditoria->cod_reg     = $output->id;
        $auditoria->status      = 1;
        $auditoria->fec_regins  = date("Y-m-d H:i:s");
        $auditoria->usr_regins  = $request["id_user"];
        $auditoria->save();
        if(isset($request["id_product"])){
            foreach($request["id_product"] as $key => $value){
                $producs_items = [];
                $producs_items["id_output"]   = $output->id;
                $producs_items["id_product"]  = $value;
                $producs_items["qty"]         = $request["qty"][$key];
                $producs_items["price"]       = str_replace(",", "", $request["price"][$key]);
                $producs_items["vat"]         = $request["vat"][$key];
                $producs_items["total"]       = str_replace(",", "", $request["total"][$key]);
                ProductusOutputItems::create($producs_items);
            }   
        }
        if ($output) {
            $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente <a href='api/invoice/print/$output->id' target='_blank'>Imprimir Factura</a>");    
            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json("A ocurrido un error")->setStatusCode(400);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\ProductusOutput  $productusOutput
     * @return \Illuminate\Http\Response
     */
    public function show(ProductusOutput $productusOutput)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductusOutput  $productusOutput
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductusOutput $productusOutput)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductusOutput  $productusOutput
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $productusOutput)
    {
        $update = ProductusOutput::find($productusOutput)->update($request->all());
        ProductusOutputItems::where("id_output", $productusOutput)->delete();
        if(isset($request["id_product"])){
            foreach($request["id_product"] as $key => $value){
                $producs_items["id_product"]  = $value;
                $producs_items["id_output"]   = $productusOutput;
                $producs_items["qty"]         = $request["qty"][$key];
                $producs_items["price"]       = str_replace(",", "", $request["price"][$key]);
                $producs_items["vat"]         = $request["vat"][$key];
                $producs_items["total"]       = str_replace(",", "", $request["total"][$key]);
                ProductusOutputItems::create($producs_items);
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
     * @param  \App\ProductusOutput  $productusOutput
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductusOutput $productusOutput)
    {
        //
    }
}
