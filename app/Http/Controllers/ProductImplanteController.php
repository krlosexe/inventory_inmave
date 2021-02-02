<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{ProductImplantes,Auditoria};
use DB;

class ProductImplanteController extends Controller
{

    public function ListProductImplante()
    {
        try {
            

            $products = ProductImplantes::select("products_implantes.*", "auditoria.*", "user_registro.email as email_regis")
            ->join("auditoria", "auditoria.cod_reg", "=", "products_implantes.id")
            ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
            ->where("auditoria.tabla", "products_implantes")
            ->where("auditoria.status", "!=", "0")
            ->orderBy("products_implantes.id", "DESC")
            ->get();

            foreach($products as $value){
                $existence = $this->GetExistence($value["id"]);
                $value["existence"] = $existence;
            }
            return response()->json($products)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function CreateProductImplante(Request $request)
    {
        try {
            
            // dd($request->all());

            $create = ProductImplantes::create($request->all());

            $auditoria              = new Auditoria;
            $auditoria->tabla       = "products_implantes";
            $auditoria->cod_reg     = $create->id;
            $auditoria->status      = 1;
            $auditoria->fec_regins  = date("Y-m-d H:i:s");
            $auditoria->usr_regins  = $request["id_user"];
            $auditoria->save();

            if($create){
                $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");    
                return response()->json($data)->setStatusCode(200);
            }else{
                return response()->json("A ocurrido un error")->setStatusCode(400);
            }

        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function EditProductImplante(Request $request,$id)
    {
        try {
            $update = ProductImplantes::find($id);
            $update->referencia = $request->referencia;
            $update->precio = $request->precio;
            $update->register_invima = $request->register_invima;
            $update->description = $request->description;
            $update->gramaje = $request->gramaje;
            $update->perfil = $request->perfil;
            $update->id_user = $request->id_user;
            $update->save();

            if ($update) {
                $data = array('mensagge' => "Los datos fueron editados satisfactoriamente");    
                return response()->json($data)->setStatusCode(200);
            }else{
                return response()->json("A ocurrido un error")->setStatusCode(400);
            }

        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function DeleteProductImplante($id)
    {
        try {
            ProductImplantes::where('id',$id)->delete();
            $data = array('mensagge' => "Los datos fueron eliminados satisfactoriamente");    
            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function GetExistence($id_product){
        try {
                    $entry_medellin = DB::table("technical_reception_products_implante")
                    ->selectRaw("technical_reception_products_implante.id_product, products_implantes.description, (count(technical_reception_products_implante.referencia))  as total")
                    ->join("technical_reception_implante", "technical_reception_implante.id", "technical_reception_products_implante.id_technical_reception_implante")
                    ->join("products_implantes", "products_implantes.id", "technical_reception_products_implante.id_product")
                    ->where("technical_reception_implante.warehouse", "Medellin")
                    ->where("products_implantes.id", $id_product)
                    ->groupBy("technical_reception_products_implante.referencia")
                    ->first();

                    $output_medellin = DB::table("implantes_output_items")
                    ->selectRaw("implantes_output_items.id_product, products_implantes.description, (count(implantes_output_items.referencia))  as total")
                    ->join("implantes_output", "implantes_output.id", "implantes_output_items.id_implant_output")
                    ->join("products_implantes", "products_implantes.id", "implantes_output_items.id_product")
                    ->where("implantes_output.warehouse", "Medellin")
                    ->where("products_implantes.id", $id_product)
                    ->groupBy("implantes_output_items.referencia")
                    ->first();

                    // $traspase_medellin = DB::table("product_output_items_trapase")
                    // ->selectRaw("product_output_items_trapase.id_product, products.description, (SUM(product_output_items_trapase.qty))  as total")
                    // ->join("product_output_traspase", "product_output_traspase.id", "product_output_items_trapase.id_output_traspase")
                    // ->join("products", "products.id", "product_output_items_trapase.id_product")
                    // ->where("product_output_traspase.warehouse", "Medellin")
                    // ->where("products.id", $id_product)
                    // ->groupBy("product_output_items_trapase.id_product")
                    // ->first();


                    $output_medellin_reemision = DB::table("implantes_reemisiones_items")
                    ->selectRaw("implantes_reemisiones_items.id_product, products_implantes.description, (count(implantes_reemisiones_items.referencia))  as total")
                    ->join("implantes_reemisiones", "implantes_reemisiones.id", "implantes_reemisiones_items.id_implante_reemision")
                    ->join("products_implantes", "products_implantes.id", "implantes_reemisiones_items.id_product")
                    ->where("implantes_reemisiones.warehouse", "Medellin")
                    ->where("products_implantes.id", $id_product)
                    ->groupBy("implantes_reemisiones_items.referencia")
                    ->first();


                        $entry_bogota = DB::table("technical_reception_products_implante")
                        ->selectRaw("technical_reception_products_implante.id_product, products_implantes.description, (count(technical_reception_products_implante.referencia))  as total")
                        ->join("technical_reception_implante", "technical_reception_implante.id", "technical_reception_products_implante.id_technical_reception_implante")
                        ->join("products_implantes", "products_implantes.id", "technical_reception_products_implante.id_product")
                        ->where("technical_reception_implante.warehouse", "Bogota")
                        ->where("products_implantes.id", $id_product)
                        ->groupBy("technical_reception_products_implante.referencia")
                        ->first();

                        $output_bogota = DB::table("implantes_output_items")
                        ->selectRaw("implantes_output_items.id_product, products_implantes.description, (count(implantes_output_items.referencia))  as total")
                        ->join("implantes_output", "implantes_output.id", "implantes_output_items.id_implant_output")
                        ->join("products_implantes", "products_implantes.id", "implantes_output_items.id_product")
                        ->where("implantes_output.warehouse", "Bogota")
                        ->where("products_implantes.id", $id_product)
                        ->groupBy("implantes_output_items.referencia")
                        ->first();

                        // $traspase_bogota = DB::table("product_output_items_trapase")
                        // ->selectRaw("product_output_items_trapase.id_product, products.description, (SUM(product_output_items_trapase.qty))  as total")
                        // ->join("product_output_traspase", "product_output_traspase.id", "product_output_items_trapase.id_output_traspase")
                        // ->join("products", "products.id", "product_output_items_trapase.id_product")
                        // ->where("product_output_traspase.warehouse", "Bogota")
                        // ->where("products.id", $id_product)
                        // ->groupBy("product_output_items_trapase.id_product")
                        // ->first();

                        $output_bogota_reemision = DB::table("implantes_reemisiones_items")
                        ->selectRaw("implantes_reemisiones_items.id_product, products_implantes.description, (count(implantes_reemisiones_items.referencia))  as total")
                        ->join("implantes_reemisiones", "implantes_reemisiones.id", "implantes_reemisiones_items.id_implante_reemision")
                        ->join("products_implantes", "products_implantes.id", "implantes_reemisiones_items.id_product")
                        ->where("implantes_reemisiones.warehouse", "Bogota")
                        ->where("products_implantes.id", $id_product)
                        ->groupBy("implantes_reemisiones_items.referencia")
                        ->first();

                        $entry_cali = DB::table("technical_reception_products_implante")
                        ->selectRaw("technical_reception_products_implante.id_product, products_implantes.description, (count(technical_reception_products_implante.referencia))  as total")
                        ->join("technical_reception_implante", "technical_reception_implante.id", "technical_reception_products_implante.id_technical_reception_implante")
                        ->join("products_implantes", "products_implantes.id", "technical_reception_products_implante.id_product")
                        ->where("technical_reception_implante.warehouse", "Cali")
                        ->where("products_implantes.id", $id_product)
                        ->groupBy("technical_reception_products_implante.referencia")
                        ->first();

                        $output_cali = DB::table("implantes_output_items")
                        ->selectRaw("implantes_output_items.id_product, products_implantes.description, (count(implantes_output_items.referencia))  as total")
                        ->join("implantes_output", "implantes_output.id", "implantes_output_items.id_implant_output")
                        ->join("products_implantes", "products_implantes.id", "implantes_output_items.id_product")
                        ->where("implantes_output.warehouse", "Cali")
                        ->where("products_implantes.id", $id_product)
                        ->groupBy("implantes_output_items.referencia")
                        ->first();

                        // $traspase_bogota = DB::table("product_output_items_trapase")
                        // ->selectRaw("product_output_items_trapase.id_product, products.description, (SUM(product_output_items_trapase.qty))  as total")
                        // ->join("product_output_traspase", "product_output_traspase.id", "product_output_items_trapase.id_output_traspase")
                        // ->join("products", "products.id", "product_output_items_trapase.id_product")
                        // ->where("product_output_traspase.warehouse", "Cali")
                        // ->where("products.id", $id_product)
                        // ->groupBy("product_output_items_trapase.id_product")
                        // ->first();

                        $output_cali_reemision = DB::table("implantes_reemisiones_items")
                        ->selectRaw("implantes_reemisiones_items.id_product, products_implantes.description, (count(implantes_reemisiones_items.referencia))  as total")
                        ->join("implantes_reemisiones", "implantes_reemisiones.id", "implantes_reemisiones_items.id_implante_reemision")
                        ->join("products_implantes", "products_implantes.id", "implantes_reemisiones_items.id_product")
                        ->where("implantes_reemisiones.warehouse", "Cali")
                        ->where("products_implantes.id", $id_product)
                        ->groupBy("implantes_reemisiones_items.referencia")
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
                // if($traspase_medellin){
                //     $total_traspaso_medellin = $traspase_medellin->total;
                // }
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
                // if($traspase_bogota){
                //     $total_traspaso_bogota = $traspase_bogota->total;
                // }
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
                // if($traspase_cali){
                //     $total_traspaso_cali = $traspase_cali->total;
                // }
                $data_medellin["cali"]["total"] = $entry_cali->total - $total_output_cali - $total_output_cali_reemision - $total_traspaso_cali;
            }else{
                $data_medellin["cali"]["total"] = 0;
            }
            return $data_medellin;

        } catch (\Throwable $th) {
            return $th;
        }
    }

}
