<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{MedgelOutput,MedgelOutputItems,Auditoria};

class MedgelOutputController extends Controller
{
    public function ListMedgelOutput()
    {
        $data = MedgelOutput::select("medgel_output.*", "implantes_clients.name as name_client", "auditoria.*", "user_registro.email as email_regis")
            ->join("auditoria", "auditoria.cod_reg", "=", "medgel_output.id")
            ->join("implantes_clients", "implantes_clients.id", "=", "medgel_output.id_client")
            ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
            ->where("auditoria.tabla", "medgel_output")
            ->where("auditoria.status", "!=", "0")
            ->orderBy("medgel_output.id", "DESC")
            ->with("items")
            ->get();
        
            return $data;

    }
    public function CreateMedgelOutput(Request $request)
    {
        try {
            isset($request["reissue"])  ? $request["reissue"] = 1 : $request["reissue"] = 0;
            $output = new MedgelOutput;
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
            $output->save();

            $auditoria              = new Auditoria;
            $auditoria->tabla       = "medgel_output";
            $auditoria->cod_reg     = $output->id;
            $auditoria->status      = 1;
            $auditoria->fec_regins  = date("Y-m-d H:i:s");
            $auditoria->usr_regins  = $request["id_user"];
            $auditoria->save();

                foreach ($request->referencia as $key => $value) {
                    $producs_items = [];
                    $producs_items["id_implante_reemision"] = $output->id;
                    $producs_items["referencia"]  = $value;
                    $producs_items["serial"]      = $request["serial"][$key];
                    $producs_items["qty"]         = $request["qty"][$key];
                    $producs_items["price"]       = str_replace(",", "", $request["price"][$key]);
                    $producs_items["vat"]         = 1;
                    $producs_items["total"]       = str_replace(",", "", $request->total_invoice);

                    MedgelOutputItems::create($producs_items);
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
    public function UpdateMedgelOutput(Request $request, $output)
    {
        $update = MedgelOutput::find($output);
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
        $update->save();

        MedgelOutputItems::where("id_medgel_output",$output)->delete();
        if(isset($request->referencia)){
            foreach($request->referencia as $key => $value){
                $producs_items=[];
                $producs_items["id_medgel_output"]  = $update->id;
                $producs_items["id_product"]    = $value;
                $producs_items["lote"]        = $request["serial"][$key];
                $producs_items["qty"]           = $request["qty"][$key];
                $producs_items["price"]         = str_replace(",", "", $request["price"][$key]);
                $producs_items["vat"]           = $request["vat"][$key];
                $producs_items["total"]         = str_replace(",", "", $request["total"][$key]);
                MedgelOutputItems::create($producs_items);

            }
        }
        if ($update) {
            $data = array('mensagge' => "Los datos fueron editados satisfactoriamente");
            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json("A ocurrido un error")->setStatusCode(400);
        }
    }
}
