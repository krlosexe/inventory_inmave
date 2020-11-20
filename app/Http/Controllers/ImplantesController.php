<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{
    TechnicalReceptionProductoImplante,
    ImplanteReemision,
    ImplanteReemisionesItem,
    Auditoria
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
        try {

            // dd($request->all());
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
            $output->save();

            $auditoria              = new Auditoria;
            $auditoria->tabla       = "implantes_reemisiones";
            $auditoria->cod_reg     = $output->id;
            $auditoria->status      = 1;
            $auditoria->fec_regins  = date("Y-m-d H:i:s");
            $auditoria->usr_regins  = $request["id_user"];
            $auditoria->save();

            if (isset($request["serial"])) {
                foreach ($request["serial"] as $key => $value) {

                    $producs_items["id_implante_reemision"] = $output->id;
                    $producs_items["serial"]       = $value;
                    $producs_items["qty"]         = $request["qty"][$key];
                    $producs_items["price"]       = str_replace(",", "", $request["price"][$key]);
                    $producs_items["vat"]         = 1;
                    $producs_items["total"]       = str_replace(",", "", $request["total"][$key]);

                    ImplanteReemisionesItem::create($producs_items);
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

    public function ListImplanteRemision()
    {
        $data = ImplanteReemision::select("implantes_reemisiones.*", "clients.name as name_client", "auditoria.*", "user_registro.email as email_regis")
            ->join("auditoria", "auditoria.cod_reg", "=", "implantes_reemisiones.id")
            ->join("clients", "clients.id", "=", "implantes_reemisiones.id_client")
            ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
            ->where("auditoria.tabla", "implantes_reemisiones")
            ->where("auditoria.status", "!=", "0")
            ->orderBy("implantes_reemisiones.id", "DESC")
            ->with("items")
            ->get();

        return response()->json($data)->setStatusCode(200);
    }
}
