<?php

namespace App\Http\Controllers;

use App\{Products};
use App\Auditoria;
use DB;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Products::select("products.*", "auditoria.*", "user_registro.email as email_regis")
                            ->join("auditoria", "auditoria.cod_reg", "=", "products.id")
                            ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
                            ->where("auditoria.tabla", "products")
                            ->where("auditoria.status", "!=", "0")
                           // ->where("products.id", 7)
                            ->orderBy("products.id", "DESC")
                            ->get();

        foreach($products as $value){
            $existence = $this->GetExistence($value["id"]);

            $value["existence"] = $existence;
        }
        return response()->json($products)->setStatusCode(200);
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
        $config               = DB::table("config")->first();
        $request["price_cop"] = $config->valuation_euro * $request["price_euro"];

        $products = Products::create($request->all());

        $auditoria              = new Auditoria;
        $auditoria->tabla       = "products";
        $auditoria->cod_reg     = $products->id;
        $auditoria->status      = 1;
        $auditoria->fec_regins  = date("Y-m-d H:i:s");
        $auditoria->usr_regins  = $request["id_user"];
        $auditoria->save();

        if ($products) {
            $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente", "data" => $products);
            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json("A ocurrido un error")->setStatusCode(400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function show($products)
    {
        $products = Products::select("products.*", "auditoria.*", "user_registro.email as email_regis")
                            ->join("auditoria", "auditoria.cod_reg", "=", "products.id")
                            ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
                            ->where("auditoria.tabla", "products")
                            ->where("products.id", $products)
                            ->where("auditoria.status", "!=", "0")
                            ->orderBy("products.id", "DESC")
                            ->first();
        return response()->json($products)->setStatusCode(200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit(Products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $products)
    {

        $config               = DB::table("config")->first();
        $request["price_cop"] = $config->valuation_euro * $request["price_euro"];


        $update_product = Products::find($products)->update($request->all());

        if ($update_product) {
            $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json("A ocurrido un error")->setStatusCode(400);
        }
    }




    public function GetExistence($id_product){


        $entry_medellin = DB::table("product_entry_items")
                            ->selectRaw("product_entry_items.id_product, products.description, (SUM(product_entry_items.qty))  as total")
                            ->join("products_entry", "products_entry.id", "product_entry_items.id_entry")
                            ->join("products", "products.id", "product_entry_items.id_product")
                            ->where("products_entry.warehouse", "Medellin")
                            ->where("products.id", $id_product)
                            ->groupBy("product_entry_items.id_product")
                            ->first();

        $output_medellin = DB::table("product_output_items")
                            ->selectRaw("product_output_items.id_product, products.description, (SUM(product_output_items.qty))  as total")
                            ->join("product_output", "product_output.id", "product_output_items.id_output")
                            ->join("products", "products.id", "product_output_items.id_product")
                            ->where("product_output.warehouse", "Medellin")
                            ->where("products.id", $id_product)
                            ->groupBy("product_output_items.id_product")
                            ->first();




       $traspase_medellin = DB::table("product_output_items_trapase")
                            ->selectRaw("product_output_items_trapase.id_product, products.description, (SUM(product_output_items_trapase.qty))  as total")
                            ->join("product_output_traspase", "product_output_traspase.id", "product_output_items_trapase.id_output_traspase")
                            ->join("products", "products.id", "product_output_items_trapase.id_product")
                            ->where("product_output_traspase.warehouse", "Medellin")
                            ->where("products.id", $id_product)
                            ->groupBy("product_output_items_trapase.id_product")
                            ->first();

                            // dd($traspase_medellin);



        $output_medellin_reemision = DB::table("reemisiones_items")
                            ->selectRaw("reemisiones_items.id_product, products.description, (SUM(reemisiones_items.qty))  as total")
                            ->join("reemisiones", "reemisiones.id", "reemisiones_items.id_reemision")
                            ->join("products", "products.id", "reemisiones_items.id_product")
                            ->where("reemisiones.warehouse", "Medellin")
                            ->where("products.id", $id_product)
                            ->groupBy("reemisiones_items.id_product")
                            ->first();




        $entry_bogota = DB::table("product_entry_items")
                            ->selectRaw("product_entry_items.id_product, products.description, (SUM(product_entry_items.qty))  as total")
                            ->join("products_entry", "products_entry.id", "product_entry_items.id_entry")
                            ->join("products", "products.id", "product_entry_items.id_product")
                            ->where("products_entry.warehouse", "Bogota")
                            ->where("products.id", $id_product)
                            ->groupBy("product_entry_items.id_product")
                            ->first();

        $output_bogota = DB::table("product_output_items")
                            ->selectRaw("product_output_items.id_product, products.description, (SUM(product_output_items.qty))  as total")
                            ->join("product_output", "product_output.id", "product_output_items.id_output")
                            ->join("products", "products.id", "product_output_items.id_product")
                            ->where("product_output.warehouse", "Bogota")
                            ->where("products.id", $id_product)
                            ->groupBy("product_output_items.id_product")
                            ->first();

         $traspase_bogota = DB::table("product_output_items_trapase")
                            ->selectRaw("product_output_items_trapase.id_product, products.description, (SUM(product_output_items_trapase.qty))  as total")
                            ->join("product_output_traspase", "product_output_traspase.id", "product_output_items_trapase.id_output_traspase")
                            ->join("products", "products.id", "product_output_items_trapase.id_product")
                            ->where("product_output_traspase.warehouse", "Bogota")
                            ->where("products.id", $id_product)
                            ->groupBy("product_output_items_trapase.id_product")
                            ->first();



        $output_bogota_reemision = DB::table("reemisiones_items")
                            ->selectRaw("reemisiones_items.id_product, products.description, (SUM(reemisiones_items.qty))  as total")
                            ->join("reemisiones", "reemisiones.id", "reemisiones_items.id_reemision")
                            ->join("products", "products.id", "reemisiones_items.id_product")
                            ->where("reemisiones.warehouse", "Bogota")
                            ->where("products.id", $id_product)
                            ->groupBy("reemisiones_items.id_product")
                            ->first();

        $entry_cali = DB::table("product_entry_items")
                            ->selectRaw("product_entry_items.id_product, products.description, (SUM(product_entry_items.qty))  as total")
                            ->join("products_entry", "products_entry.id", "product_entry_items.id_entry")
                            ->join("products", "products.id", "product_entry_items.id_product")
                            ->where("products_entry.warehouse", "Cali")
                            ->where("products.id", $id_product)
                            ->groupBy("product_entry_items.id_product")
                            ->first();

        $output_cali = DB::table("product_output_items")
                            ->selectRaw("product_output_items.id_product, products.description, (SUM(product_output_items.qty))  as total")
                            ->join("product_output", "product_output.id", "product_output_items.id_output")
                            ->join("products", "products.id", "product_output_items.id_product")
                            ->where("product_output.warehouse", "Cali")
                            ->where("products.id", $id_product)
                            ->groupBy("product_output_items.id_product")
                            ->first();

        $traspase_cali = DB::table("product_output_items_trapase")
                            ->selectRaw("product_output_items_trapase.id_product, products.description, (SUM(product_output_items_trapase.qty))  as total")
                            ->join("product_output_traspase", "product_output_traspase.id", "product_output_items_trapase.id_output_traspase")
                            ->join("products", "products.id", "product_output_items_trapase.id_product")
                            ->where("product_output_traspase.warehouse", "Cali")
                            ->where("products.id", $id_product)
                            ->groupBy("product_output_items_trapase.id_product")
                            ->first();



        $output_cali_reemision = DB::table("reemisiones_items")
                            ->selectRaw("reemisiones_items.id_product, products.description, (SUM(reemisiones_items.qty))  as total")
                            ->join("reemisiones", "reemisiones.id", "reemisiones_items.id_reemision")
                            ->join("products", "products.id", "reemisiones_items.id_product")
                            ->where("reemisiones.warehouse", "Cali")
                            ->where("products.id", $id_product)
                            ->groupBy("reemisiones_items.id_product")
                            ->first();



        $data_medellin = [];
        if($entry_medellin){
            $total_output_medellin           = 0;
            $total_output_medellin_reemision = 0;
            $total_traspaso_medellin = 0;

            if($output_medellin){
                $total_output_medellin = $output_medellin->total;
            }

            if($output_medellin_reemision){
                $total_output_medellin_reemision = $output_medellin_reemision->total;
            }

            if($traspase_medellin){
                $total_traspaso_medellin = $traspase_medellin->total;
            }

            $data_medellin["medellin"]["total"] = $entry_medellin->total - $total_output_medellin - $total_output_medellin_reemision - $total_traspaso_medellin;
        }else{
            $data_medellin["medellin"]["total"] = 0;
        }

        if($entry_bogota){

            $total_output_bogota           = 0;
            $total_output_bogota_reemision = 0;
            $total_traspaso_bogota = 0;
            if($output_bogota){
                $total_output_bogota = $output_bogota->total;
            }


            if($output_bogota_reemision){
                $total_output_bogota_reemision = $output_bogota_reemision->total;
            }

            if($traspase_bogota){
                $total_traspaso_bogota = $traspase_bogota->total;
            }



            $data_medellin["bogota"]["total"] = $entry_bogota->total - $total_output_bogota - $total_output_bogota_reemision - $total_traspaso_bogota;
        }else{
            $data_medellin["bogota"]["total"] = 0;
        }

        if($entry_cali){

            $total_output_cali           = 0;
            $total_output_cali_reemision = 0;
            $total_traspaso_cali = 0;
            if($output_cali){
                $total_output_cali = $output_cali->total;
            }
            if($output_cali_reemision){
                $total_output_cali_reemision = $output_cali_reemision->total;
            }

            if($traspase_cali){
                $total_traspaso_cali = $traspase_cali->total;
            }

            $data_medellin["cali"]["total"] = $entry_cali->total - $total_output_cali - $total_output_cali_reemision - $total_traspaso_cali;
        }else{
            $data_medellin["cali"]["total"] = 0;
        }

        return $data_medellin;

    }



    public function GetExistenceWarehouse($warehouse){

        $entry = DB::table("product_entry_items")
                    ->selectRaw("product_entry_items.id_product, products.description,products.price_euro,product_entry_items.lote,product_entry_items.register_invima, product_entry_items.date_expiration, (SUM(product_entry_items.qty))  as total, products.presentation, products.price_cop, products.price_distributor_x_caja, products.price_distributor_x_vial, products.price_cliente_x_caja, products.price_cliente_x_vial")
                    ->join("products_entry", "products_entry.id", "product_entry_items.id_entry")
                    ->join("products", "products.id", "product_entry_items.id_product")
                    ->where("products_entry.warehouse", $warehouse)
                    ->groupBy("product_entry_items.id_product")
                    ->get();


        $output = DB::table("product_output_items")
                    ->selectRaw("product_output_items.id_product, products.description, (SUM(product_output_items.qty))  as total, products.presentation, products.price_cop, products.price_distributor_x_caja, products.price_distributor_x_vial, products.price_cliente_x_caja, products.price_cliente_x_vial")
                    ->join("product_output", "product_output.id", "product_output_items.id_output")
                    ->join("products", "products.id", "product_output_items.id_product")
                    ->where("product_output.warehouse", $warehouse)
                    ->groupBy("product_output_items.id_product")
                    ->get();


        $output_reemision = DB::table("reemisiones_items")
                    ->selectRaw("reemisiones_items.id_product, products.description, (SUM(reemisiones_items.qty))  as total, products.presentation, products.price_cop, products.price_distributor_x_caja, products.price_distributor_x_vial, products.price_cliente_x_caja, products.price_cliente_x_vial")
                    ->join("reemisiones", "reemisiones.id", "reemisiones_items.id_reemision")
                    ->join("products", "products.id", "reemisiones_items.id_product")
                    ->where("reemisiones.warehouse", $warehouse)
                    ->groupBy("reemisiones_items.id_product")
                    ->get();




        foreach($entry as $value){
            foreach($output as $out){
                if($value->id_product == $out->id_product){
                    $value->total = $value->total - $out->total;
                }else{
                    $value->total = (int)$value->total;
                }
            }


            foreach($output_reemision as $out_reemision){
                if($value->id_product == $out_reemision->id_product){
                    $value->total = $value->total - $out_reemision->total;
                }else{
                    $value->total = (int)$value->total;
                }
            }


        }


        return response()->json($entry)->setStatusCode(200);
    }






    public function status($id, $status, Request $request)
    {

        $auditoria =  Auditoria::where("cod_reg", $id)
                                    ->where("tabla", "products")->first();

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
     * @param  \App\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(Products $products)
    {
        //
    }
}
