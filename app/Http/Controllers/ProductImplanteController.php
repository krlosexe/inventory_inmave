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


            $products = ProductImplantes::select("products_implantes.*", "user_registro.email as email_regis")
            // ->join("auditoria", "auditoria.cod_reg", "=", "products_implantes.id")
            ->join("users as user_registro", "user_registro.id", "=", "products_implantes.id_user")
            // ->where("auditoria.tabla", "products_implantes")
            // ->where("auditoria.status", "!=", "0")
            ->orderBy("products_implantes.id", "DESC")
            ->get();

            foreach($products as $value){
                $existence = $this->GetExistence($value["referencia"]);
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
                    ->selectRaw("(count(technical_reception_products_implante.referencia))  as total")
                    ->join("technical_reception_implante", "technical_reception_implante.id", "technical_reception_products_implante.id_technical_reception_implante")
                    ->where("technical_reception_implante.warehouse", "Medellin")
                    ->where("technical_reception_products_implante.referencia", $id_product)
                    ->groupBy("technical_reception_products_implante.referencia")
                    ->first();

                    $output_medellin = DB::table("implantes_output_items")
                    ->selectRaw("(count(implantes_output_items.referencia))  as total")
                    ->join("implantes_output", "implantes_output.id", "implantes_output_items.id_implant_output")
                    ->where("implantes_output.warehouse", "Medellin")
                    ->where("implantes_output_items.referencia",$id_product)
                    ->groupBy("implantes_output_items.referencia")
                    ->first();

                    // $traspase_medellin = DB::table("implante_product_output_items_trapase")
                    // ->selectRaw("(SUM(implante_product_output_items_trapase.qty))  as total")
                    // ->join("implante_product_output_traspase", "implante_product_output_traspase.id", "implante_product_output_items_trapase.id_implante_output_traspase")
                    // ->where("implante_product_output_traspase.warehouse", "Medellin")
                    // ->where("implante_product_output_traspase.warehouse", $id_product)
                    // ->where("implante_product_output_items_trapase.referencia", $id_product)
                    // ->groupBy("implante_product_output_items_trapase.referencia")
                    // ->first();


                    $output_medellin_reemision = DB::table("implantes_reemisiones_items")
                    ->selectRaw("(count(implantes_reemisiones_items.referencia))  as total")
                    ->join("implantes_reemisiones", "implantes_reemisiones.id", "implantes_reemisiones_items.id_implante_reemision")
                    ->where("implantes_reemisiones.warehouse", "Medellin")
                    ->where("implantes_reemisiones_items.referencia", $id_product)
                    ->groupBy("implantes_reemisiones_items.referencia")
                    ->first();


                        $entry_bogota = DB::table("technical_reception_products_implante")
                        ->selectRaw(" (count(technical_reception_products_implante.referencia))  as total")
                        ->join("technical_reception_implante", "technical_reception_implante.id", "technical_reception_products_implante.id_technical_reception_implante")
                        ->where("technical_reception_implante.warehouse", "Bogota")
                        ->where("technical_reception_products_implante.referencia", $id_product)
                        ->groupBy("technical_reception_products_implante.referencia")
                        ->first();

                        $output_bogota = DB::table("implantes_output_items")
                        ->selectRaw("(count(implantes_output_items.referencia))  as total")
                        ->join("implantes_output", "implantes_output.id", "implantes_output_items.id_implant_output")
                        ->where("implantes_output.warehouse", "Bogota")
                        ->where("implantes_output_items.referencia", $id_product)
                        ->groupBy("implantes_output_items.referencia")
                        ->first();

                        // $traspase_bogota = DB::table("implante_product_output_items_trapase")
                        // ->selectRaw("(SUM(implante_product_output_items_trapase.qty))  as total")
                        // ->join("implante_product_output_traspase", "implante_product_output_traspase.id", "implante_product_output_items_trapase.id_implante_output_traspase")
                        // ->where("implante_product_output_traspase.warehouse", "Bogota")
                        // ->where("implante_product_output_items_trapase.referencia", "Bogota")
                        // ->where("implante_product_output_items_trapase.referencia", $id_product)
                        // ->groupBy("implante_product_output_items_trapase.referencia")
                        // ->first();

                        $output_bogota_reemision = DB::table("implantes_reemisiones_items")
                        ->selectRaw("(count(implantes_reemisiones_items.referencia))  as total")
                        ->join("implantes_reemisiones", "implantes_reemisiones.id", "implantes_reemisiones_items.id_implante_reemision")
                        ->where("implantes_reemisiones.warehouse", "Bogota")
                        ->where("implantes_reemisiones_items.referencia", $id_product)
                        ->groupBy("implantes_reemisiones_items.referencia")
                        ->first();

                        $entry_cali = DB::table("technical_reception_products_implante")
                        ->selectRaw("(count(technical_reception_products_implante.referencia))  as total")
                        ->join("technical_reception_implante", "technical_reception_implante.id", "technical_reception_products_implante.id_technical_reception_implante")
                        ->where("technical_reception_implante.warehouse", "Cali")
                        ->where("technical_reception_products_implante.referencia", $id_product)
                        ->groupBy("technical_reception_products_implante.referencia")
                        ->first();

                        $output_cali = DB::table("implantes_output_items")
                        ->selectRaw(" (count(implantes_output_items.referencia))  as total")
                        ->join("implantes_output", "implantes_output.id", "implantes_output_items.id_implant_output")
                        ->where("implantes_output.warehouse", "Cali")
                        ->where("implantes_output_items.referencia", $id_product)
                        ->groupBy("implantes_output_items.referencia")
                        ->first();

                        // $traspase_cali = DB::table("implante_product_output_items_trapase")
                        // ->selectRaw("(SUM(implante_product_output_items_trapase.qty))  as total")
                        // ->join("implante_product_output_traspase", "implante_product_output_traspase.id", "implante_product_output_items_trapase.id_implante_output_traspase")
                        // ->where("implante_product_output_traspase.warehouse", "Cali")
                        // ->where("implante_product_output_items_trapase.referencia",$id_product)
                        // ->groupBy("implante_product_output_items_trapase.referencia")
                        // ->first();

                        $output_cali_reemision = DB::table("implantes_reemisiones_items")
                        ->selectRaw("(count(implantes_reemisiones_items.referencia))  as total")
                        ->join("implantes_reemisiones", "implantes_reemisiones.id", "implantes_reemisiones_items.id_implante_reemision")
                        ->where("implantes_reemisiones.warehouse", "Cali")
                        ->where("implantes_reemisiones_items.referencia", $id_product)
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
    public function GetExistenceWarehouse($warehouse){

        $entry = DB::table("technical_reception_products_implante")
                    ->selectRaw("technical_reception_products_implante.referencia,technical_reception_products_implante.serial,(count(technical_reception_products_implante.referencia))  as total")
                    ->join("technical_reception_implante", "technical_reception_implante.id", "technical_reception_products_implante.id_technical_reception_implante")
                    ->where("technical_reception_implante.warehouse", $warehouse)
                    ->where("technical_reception_products_implante.estatus","Disponible")
                    ->groupBy("technical_reception_products_implante.referencia")
                    ->get();

        
        $output = DB::table("implantes_output_items")
                    ->selectRaw("implantes_output_items.referencia,implantes_output_items.serial, (count(implantes_output_items.referencia))  as total")
                    ->join("implantes_output", "implantes_output.id", "implantes_output_items.id_implant_output")
                    ->where("implantes_output.warehouse", $warehouse)
                    ->groupBy("implantes_output_items.referencia")
                    ->get();

        $output_reemision = DB::table("implantes_reemisiones_items")
                    ->selectRaw("implantes_reemisiones_items.referencia,implantes_reemisiones_items.serial,(count(implantes_reemisiones_items.referencia))  as total")
                    ->join("implantes_reemisiones", "implantes_reemisiones.id", "implantes_reemisiones_items.id_implante_reemision")
                    ->where("implantes_reemisiones.warehouse", $warehouse)
                    ->groupBy("implantes_reemisiones_items.referencia")
                    ->get();


        $traspase     = DB::table("implante_product_output_items_trapase")
                    ->selectRaw("implante_product_output_items_trapase.referencia,implante_product_output_items_trapase.serial,(SUM(implante_product_output_items_trapase.qty))  as total")
                    ->join("implante_product_output_traspase", "implante_product_output_traspase.id", "implante_product_output_items_trapase.id_implante_output_traspase")
                    ->where("implante_product_output_traspase.warehouse", $warehouse)
                    ->groupBy("implante_product_output_items_trapase.serial")
                    ->get();

        foreach($entry as $value){
            foreach($output as $out){
                if($value->referencia == $out->referencia){
                    $value->total = $value->total - $out->total;
                }else{
                    $value->total = (int)$value->total;
                }
            }
            foreach($output_reemision as $out_reemision){
                if($value->referencia == $out_reemision->referencia){
                    $value->total = $value->total - $out_reemision->total;
                }else{
                    $value->total = (int)$value->total;
                }
            }

            foreach($traspase as $out_traspase){
                if($value->referencia == $out_traspase->referencia){
                    $value->total = $value->total - $out_traspase->total;
                }else{
                    $value->total = (int)$value->total;
                }
            }

        }
        // dd($entry);
        return response()->json($entry)->setStatusCode(200);
    }

}
