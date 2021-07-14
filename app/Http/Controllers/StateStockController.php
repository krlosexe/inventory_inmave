<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Products;
use App\Auditoria;
use DB;

class StateStockController extends Controller
{
    public function listStateStock(Request $request)
    {
        // dd($request->bodega);
        try {
            $products = Products::select("products.*","product_output.warehouse", "auditoria.*", "user_registro.email as email_regis")
            ->join("auditoria", "auditoria.cod_reg", "=", "products.id")
            ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
            ->join("product_output_items", "products.id", "product_output_items.id_product")
            ->join("product_output", "product_output_items.id_output", "product_output.id")
            ->where("auditoria.tabla", "products")
            ->where("auditoria.status", "!=", "0") 
            ->where("product_output.warehouse", $request->bodega)
            ->orderBy("products.id", "DESC")
            ->groupBy("products.id")
            ->get();

            foreach($products as $value){
                $existence = $this->GetExistence($value["id"],$request->bodega);
                $value["existence"] = $existence;
            }
            return response()->json($products)->setStatusCode(200);
           

        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function GetExistence($id_product,$bodega){
            //   dd($bodega);
        try {
                $entry = DB::table("product_entry_items")
                            ->selectRaw("product_entry_items.id_product, products.description, (SUM(product_entry_items.qty))  as total")
                            ->join("products_entry", "products_entry.id", "product_entry_items.id_entry")
                            ->join("products", "products.id", "product_entry_items.id_product")
                            ->where("products_entry.warehouse", $bodega)
                            ->where("products.id", $id_product)
                            ->groupBy("product_entry_items.id_product")
                            ->first();

                $output = DB::table("product_output_items")
                            ->selectRaw("product_output_items.id_product, products.description, (SUM(product_output_items.qty))  as total")
                            ->join("product_output", "product_output.id", "product_output_items.id_output")
                            ->join("products", "products.id", "product_output_items.id_product")
                            ->where("product_output.warehouse", $bodega)
                            ->where("products.id", $id_product)
                            ->groupBy("product_output_items.id_product")
                            ->first();

                $output_reemision = DB::table("reemisiones_items")
                            ->selectRaw("reemisiones_items.id_product, products.description, (SUM(reemisiones_items.qty))  as total")
                            ->join("reemisiones", "reemisiones.id", "reemisiones_items.id_reemision")
                            ->join("products", "products.id", "reemisiones_items.id_product")
                            ->where("reemisiones.warehouse",$bodega)
                            ->where("products.id", $id_product)
                            ->groupBy("reemisiones_items.id_product")
                            ->first();


                $array = [];
                    if($entry){
                        $total_output = 0;
                        $total_output_reemision = 0;

                    if($output){
                        $total_output = $output->total;
                    }
                    if($output_reemision){
                        $total_output_reemision = $output_reemision->total;
                    }
                    $data["total"] = $entry->total - $total_output - $total_output_reemision;
                    }else{
                        $data["total"] = 0;
                    }
                return $data["total"];
        } catch (\Throwable $th) {
            return $th;
        }
 
    }
}
