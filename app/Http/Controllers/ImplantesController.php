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
            $data = TechnicalReceptionProductoImplante::where('serial', $serial)->first();
            return response()->json($data)->setStatusCode(200);
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
            $output->subtotal               = $request->subtotal;
            $output->subtotal_with_discount = $request->subtotal_with_discount;
            $output->vat_total                = $request->vat_total;
            $output->discount_total         = $request->discount_total;
            $output->rte_fuente             = $request->rte_fuente;
            $output->rte_fuente_total       = $request->rte_fuente_total;
            $output->total_invoice          = $request->total_invoice;
            $output->estatus                = "Remitido";
            $output->save();

            $auditoria              = new Auditoria;
            $auditoria->tabla       = "implantes_remision";
            $auditoria->cod_reg     = $output->id;
            $auditoria->status      = 1;
            $auditoria->fec_regins  = date("Y-m-d H:i:s");
            $auditoria->usr_regins  = $request["id_user"];
            $auditoria->save();

            // if (isset($request["referencia"])) {
                foreach ($request->referencia as $key => $value) {
                    $producs_items = [];
                    $producs_items["id_implante_reemision"] = $output->id;
                    $producs_items["referencia"]  = $value;
                    $producs_items["serial"]      = $request["serial"][$key];
                    $producs_items["qty"]         = $request["qty"][$key];
                    $producs_items["price"]       = str_replace(",", "", $request["price"][$key]);
                    $producs_items["vat"]         = 1;
                    $producs_items["total"]       = str_replace(",", "", $request->total_invoice);
                    // $producs_items["estatus"]     = "Remitido";

                    ImplanteReemisionesItem::create($producs_items);

                    $detail = TechnicalReceptionProductoImplante::where('serial',$request["serial"])->first();
                    TechnicalReceptionImplante::where("id",$detail->id_technical_reception_implante)->update(["estatus" => "Remitido"]);
                }
            // }
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
            $update->subtotal               = $request->subtotal;
            $update->subtotal_with_discount = $request->subtotal_with_discount;
            $update->vat_total                = $request->vat_total;
            $update->discount_total         = $request->discount_total;
            $update->rte_fuente             = $request->rte_fuente;
            $update->rte_fuente_total       = $request->rte_fuente_total;
            $update->total_invoice          = $request->total_invoice;
            $update->estatus                = "Remitido";
            $update->save();

            ImplanteReemisionesItem::where("id_implante_reemision", $remision)->delete();
            // if (isset($request["referencia"])) {
                foreach ($request->referencia as $key => $value) {
                    $producs_items = [];
                    $producs_items["id_implante_reemision"] = $update->id;
                    $producs_items["referencia"]  = $value;
                    $producs_items["serial"]      = $request["serial"][$key];
                    $producs_items["qty"]         = $request["qty"][$key];
                    $producs_items["price"]       = str_replace(",", "", $request["price"][$key]);
                    $producs_items["vat"]         = 1;
                    $producs_items["total"]       = str_replace(",", "", $request->total_invoice);
                    // $producs_items["estatus"]     = "Remitido";
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
        $data = ImplanteReemision::select("implantes_reemisiones.*", "clients.name as name_client", "auditoria.*", "user_registro.email as email_regis")
            ->join("auditoria", "auditoria.cod_reg", "=", "implantes_reemisiones.id")
            ->join("clients", "clients.id", "=", "implantes_reemisiones.id_client")
            ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
            ->where("auditoria.tabla", "implantes_reemisiones")
            ->where("auditoria.status", "!=", "0")
            ->where("implantes_reemisiones.estatus","Remitido")
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
            $output->subtotal               = $request->subtotal;
            $output->subtotal_with_discount = $request->subtotal_with_discount;
            $output->vat_total                = $request->vat_total;
            $output->discount_total         = $request->discount_total;
            $output->rte_fuente             = $request->rte_fuente;
            $output->rte_fuente_total       = $request->rte_fuente_total;
            $output->total_invoice          = $request->total_invoice;
            $output->estatus                = "Vendido";
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
                    $producs_items["referencia"]  = $value;
                    $producs_items["serial"]      = $request["serial"][$key];
                    $producs_items["qty"]         = $request["qty"][$key];
                    $producs_items["price"]       = str_replace(",", "", $request["price"][$key]);
                    $producs_items["vat"]         = 1;
                    $producs_items["total"]       = str_replace(",", "", $request->total_invoice);
                    // $producs_items["estatus"]     = "Vendido";
                    ImplantOutputItems::create($producs_items);

                    $detail = TechnicalReceptionProductoImplante::where('serial',$request["serial"])->first();
                    TechnicalReceptionImplante::where("id",$detail->id_technical_reception_implante)->update(["estatus" => "Vendido"]);
                
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
        $update = ImplantOutput::find($output);
        $update->warehouse              = $request->warehouse;
        $update->id_client              = $request->id_client;
        $update->reissue                = $request->reissue;
        $update->subtotal               = $request->subtotal;
        $update->subtotal_with_discount = $request->subtotal_with_discount;
        $update->vat_total                = $request->vat_total;
        $update->discount_total         = $request->discount_total;
        $update->rte_fuente             = $request->rte_fuente;
        $update->rte_fuente_total       = $request->rte_fuente_total;
        $update->total_invoice          = $request->total_invoice;
        $output->estatus                = "Vendido";
        $update->save();

        ImplantOutputItems::where("id_implant_output",$output)->delete();
        if(isset($request->referencia)){
            foreach($request->referencia as $key => $value){
                $producs_items["id_implant_output"]  = $update->id;
                $producs_items["referencia"]    = $value;
                $producs_items["serial"]        = $request["serial"][$key];
                $producs_items["qty"]           = $request["qty"][$key];
                $producs_items["price"]         = str_replace(",", "", $request["price"][$key]);
                $producs_items["vat"]           = $request["vat"][$key];
                $producs_items["total"]         = str_replace(",", "", $request["total"][$key]);
                // $producs_items["estatus"]     = "Vendido";
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
        $data = ImplantOutput::select("implantes_output.*", "clients.name as name_client", "auditoria.*", "user_registro.email as email_regis")
            ->join("auditoria", "auditoria.cod_reg", "=", "implantes_output.id")
            ->join("clients", "clients.id", "=", "implantes_output.id_client")
            ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
            ->where("auditoria.tabla", "products_output")
            ->where("auditoria.status", "!=", "0")
            ->where("implantes_output.estatus","Vendido")
            ->orderBy("implantes_output.id", "DESC")
            ->with("items")
            ->get();
        
            return $data;

    }
    public function searchSerial($serial):Object
    {
        try {
            $data = ProductImplantes::where('referencia', $serial)->first();
            if ($data) {
                return response()->json($data)->setStatusCode(200);
            } else {
                return ["mensaje" => "No Existe la referencia $serial "];
            }
        } catch (\Throwable $th) {
            return $th;
        }
    }
    
}
