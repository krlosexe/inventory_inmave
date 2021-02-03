<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{
    TechnicalReceptionProductoImplante,
    ImplanteReemision,
    ImplanteReemisionesItem,
    Auditoria,
    ImplantOutput,
    ImplantOutputItems,
    ProductImplantes,
    TechnicalReceptionImplante
};

class ImplantesController extends Controller
{
    public function GetExistenceImplante($serial)
    {
        try {
            $data = TechnicalReceptionProductoImplante::with('head')->where(['estatus'=>'Disponible','serial'=>$serial])->get();
            $data->map(function($item){
                    $item->products = ProductImplantes::where('referencia',$item->referencia)->first();
                return $item;
            });

            if(sizeof($data) > 0){
                return response()->json($data[0])->setStatusCode(200);
            }else {
                return response()->json(["mensaje" => "No Existe el serial $serial"])->setStatusCode(400); 
        }
        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function CreateImplanteRemision(Request $request)
    {
        // dd($request->all());
        try {
            isset($request["reissue"])  ? $request["reissue"] = 1 : $request["reissue"] = 0;
            $output = new ImplanteReemision;
            $output->warehouse              = $request->warehouse;
            $output->id_client              = $request->id_client;
            $output->reissue                = $request->reissue;
            $output->subtotal               = $request->total_invoice;
            $output->subtotal_with_discount = $request->subtotal_with_discount;
            $output->vat_total              = $request->vat_total? $request->vat_total : $request->vat_total= 0;
            $output->discount_type          = $request->discount_type ? $request->discount_type : $request->discount_type = 0;
            $output->discount_total         = $request->discount_total;
            $output->rte_fuente             = $request->rte_fuente;
            $output->rte_fuente_total       = $request->rte_fuente_total;
            $output->total_invoice          = $request->total_invoice;
            $output->save();

            $auditoria              = new Auditoria;
            $auditoria->tabla       = "implantes_remision";
            $auditoria->cod_reg     = $output->id;
            $auditoria->status      = 1;
            $auditoria->fec_regins  = date("Y-m-d H:i:s");
            $auditoria->usr_regins  = $request["id_user"];
            $auditoria->save();

                foreach ($request->referencia as $key => $value) {
                    $producs_items = [];
                    $producs_items["id_implante_reemision"] = $output->id;
                    $producs_items["id_product"]  = $request["id_product"][$key];
                    $producs_items["referencia"]  = $value;
                    $producs_items["serial"]      = $request["serial"][$key];
                    $producs_items["qty"]         = $request["qty"][$key];
                    $producs_items["price"]       = str_replace(",", "", $request["price"][$key]);
                    $producs_items["vat"]         = 0;
                    $producs_items["total"]       = str_replace(",", "", $request->total_invoice);

                    ImplanteReemisionesItem::create($producs_items);

                    TechnicalReceptionProductoImplante::where('serial',$request["serial"][$key])->update(["estatus" => "Remitido"]);
                }
            if ($output) {
                $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente <a href='api/invoice/print/$output->id' target='_blank'>Imprimir Factura</a>");
                return response()->json($data)->setStatusCode(200);
            } else {
                return response()->json("A ocurrido un error")->setStatusCode(400);
            }
        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function UpdateImplanteRemision(Request $request, $remision)
    {
        // dd($request->all());
        try {
            $update =  ImplanteReemision::find($remision);
            $update->warehouse              = $request->warehouse;
            $update->id_client              = $request->id_client;
            $update->reissue                = $request->reissue;
            $update->subtotal               = $request->total_invoice;
            $update->subtotal_with_discount = $request->subtotal_with_discount;
            $update->vat_total              = $request->vat_total? $request->vat_total : $request->vat_total= 0;
            $update->discount_type          = $request->discount_type? $request->discount_type : $request->discount_type = 0 ;
            $update->discount_total         = $request->discount_total;
            $update->rte_fuente             = $request->rte_fuente;
            $update->rte_fuente_total       = $request->rte_fuente_total;
            $update->total_invoice          = $request->total_invoice;
            $update->save();

            ImplanteReemisionesItem::where("id_implante_reemision", $remision)->delete();
            // if (isset($request["referencia"])) {
                foreach ($request["referencia"] as $key => $value) {
                    $producs_items = [];
                    $producs_items["id_implante_reemision"] = $update->id;
                    $producs_items["id_product"]  = $request["id_product"][$key];
                    $producs_items["referencia"]  = $value;
                    $producs_items["serial"]      = $request["serial"][$key];
                    $producs_items["qty"]         = $request["qty"][$key];
                    $producs_items["price"]       = str_replace(",", "", $request["total"][$key]);
                    $producs_items["vat"]         = 0;
                    $producs_items["total"]       = str_replace(",", "", $request->total_invoice);
                    ImplanteReemisionesItem::create($producs_items);                
                }
            // }
            if ($update) {
                 $data = array('mensagge' => "Los datos fueron editados satisfactoriamente");
                return response()->json($data)->setStatusCode(200);
            } else {
                return response()->json("A ocurrido un error")->setStatusCode(400);
            }
        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function ListImplanteRemision()
    {
        $data = ImplanteReemision::select("implantes_reemisiones.*", "implantes_clients.name as name_client", "auditoria.*", "user_registro.email as email_regis")
            ->join("auditoria", "auditoria.cod_reg", "=", "implantes_reemisiones.id")
            ->join("implantes_clients", "implantes_clients.id", "=", "implantes_reemisiones.id_client")
            ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
            ->where("auditoria.tabla", "implantes_remision")
            ->where("auditoria.status", "!=", "0")
            ->orderBy("implantes_reemisiones.id", "DESC")
            ->with("items")
            ->get();

        return response()->json($data)->setStatusCode(200);
    }
    public function CreateImplanteOutput(Request $request)
    {
        // dd($request->all());
        try {
            isset($request["reissue"])  ? $request["reissue"] = 1 : $request["reissue"] = 0;
            $output = new ImplantOutput;
            $output->warehouse              = $request->warehouse;
            $output->id_client              = $request->id_client;
            $output->reissue                = $request->reissue;
            $output->subtotal               = $request->total_invoice;
            $output->subtotal_with_discount = $request->subtotal_with_discount;
            $output->vat_total              = $request->vat_total? $request->vat_total : $request->vat_total= 0;
            $output->discount_type          = $request->discount_type ? $request->discount_type : $request->discount_type = 0;
            $output->discount_total         = $request->discount_total ;
            $output->rte_fuente             = $request->rte_fuente;
            $output->rte_fuente_total       = $request->rte_fuente_total;
            $output->total_invoice          = $request->total_invoice;
            $output->save();

            $auditoria              = new Auditoria;
            $auditoria->tabla       = "implantes_output";
            $auditoria->cod_reg     = $output->id;
            $auditoria->status      = 1;
            $auditoria->fec_regins  = date("Y-m-d H:i:s");
            $auditoria->usr_regins  = $request["id_user"];
            $auditoria->save();

            if (isset($request->referencia)) {
                foreach ($request->referencia as $key => $value) {
                    $producs_items = [];
                    $producs_items["id_implant_output"] = $output->id;
                    $producs_items["id_product"]  = $request["id_product"][$key];
                    $producs_items["referencia"]  = $value;
                    $producs_items["serial"]      = $request["serial"][$key];
                    $producs_items["qty"]         = $request["qty"][$key];
                    $producs_items["price"]       = str_replace(",", "", $request["price"][$key]);
                    $producs_items["vat"]         = 0;
                    $producs_items["total"]       = str_replace(",", "", $request->total_invoice);
                    ImplantOutputItems::create($producs_items);

                    TechnicalReceptionProductoImplante::where('serial',$request["serial"][$key])->update(["estatus" => "Vendido"]);
                }
            }
            if ($output) {
                $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente <a href='api/invoice/print/$output->id' target='_blank'>Imprimir Factura</a>");
                return response()->json($data)->setStatusCode(200);
            } else {
                return response()->json("A ocurrido un error")->setStatusCode(400);
            }
        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function UpdateImplanteOutput(Request $request, $output)
    {
        // dd($request->all());
        $update = ImplantOutput::find($output);
        $update->warehouse              = $request->warehouse;
        $update->id_client              = $request->id_client;
        $update->reissue                = $request->reissue;
        $update->subtotal               = $request->total_invoice;
        $update->subtotal_with_discount = $request->subtotal_with_discount;
        $update->vat_total              = $request->vat_total? $request->vat_total : $request->vat_total= 0;
        $update->discount_type          = $request->discount_type ? $request->discount_type : $request->discount_type = 0;
        $update->discount_total         = $request->discount_total;
        $update->rte_fuente             = $request->rte_fuente;
        $update->rte_fuente_total       = $request->rte_fuente_total;
        $update->total_invoice          = $request->total_invoice;
        $update->save();

        ImplantOutputItems::where("id_implant_output",$output)->delete();
        if(isset($request->referencia)){
            foreach($request['referencia'] as $key => $value){
                $producs_items= [];
                $producs_items["id_implant_output"]  = $update->id;
                $producs_items["id_product"]    = $request["id_product"][$key];
                $producs_items["referencia"]    = $value;
                $producs_items["serial"]        = $request["serial"][$key];
                $producs_items["qty"]           = $request["qty"][$key];
                $producs_items["price"]         = str_replace(",", "", $request["total"][$key]);
                $producs_items["vat"]           = 0;
                $producs_items["total"]         = str_replace(",", "", $request->total_invoice);
                ImplantOutputItems::create($producs_items);

            }
        }
        if ($update) {
            $data = array('mensagge' => "Los datos fueron editados satisfactoriamente");
            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json("A ocurrido un error")->setStatusCode(400);
        }
    }
    public function ListImplanteOutput()
    {
        $data = ImplantOutput::select("implantes_output.*", "implantes_clients.name as name_client", "auditoria.*", "user_registro.email as email_regis")
            ->join("auditoria", "auditoria.cod_reg", "=", "implantes_output.id")
            ->join("implantes_clients", "implantes_clients.id", "=", "implantes_output.id_client")
            ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
            ->where("auditoria.tabla", "implantes_output")
            ->where("auditoria.status", "!=", "0")
            ->orderBy("implantes_output.id", "DESC")
            ->with("items")
            ->get();
            return $data;
    }
    public function searchSerial($ref)
    {
        try {
            $data = ProductImplantes::where('referencia', $ref)->first();
            if ($data) {
                return response()->json($data)->setStatusCode(200);
            } else {
                return response()->json(["mensaje" => "No Existe la referencia $ref"])->setStatusCode(400); 
            }
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
