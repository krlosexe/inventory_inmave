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
            // $data = TechnicalReceptionProductoImplante::with('head')->where(['estatus' => 'Disponible', 'serial' => $serial])->get();

            $data = TechnicalReceptionProductoImplante::select("technical_reception_implante.*","technical_reception_products_implante.*")
            ->join("technical_reception_implante", "technical_reception_products_implante.id_technical_reception_implante","technical_reception_implante.id")
            ->where('technical_reception_products_implante.serial',$serial)
            ->where('technical_reception_products_implante.estatus','Disponible')
            ->orderBy("technical_reception_implante.created_at","DESC")
            ->get();

            $data->map(function ($item) {
                $item->products = ProductImplantes::where('referencia', $item->referencia)->first();
                return $item;
            });

            if (sizeof($data) > 0) {
                return response()->json($data[0])->setStatusCode(200);
            } else {
                return response()->json(["mensaje" => "No Existe el serial $serial"])->setStatusCode(400);
            }
        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function CreateImplanteRemision(Request $request)
    {
        try {
            
            isset($request["reissue"])  ? $request["reissue"] = 1 : $request["reissue"] = 0;
            $output = new ImplanteReemision;
            $output->warehouse              = $request->warehouse;
            $output->id_client              = $request->id_client;
            $output->reissue                = $request->reissue;
            $output->subtotal               = $request->total_invoice;
            $output->subtotal_with_discount = $request->subtotal_with_discount;
            $output->vat_total              = $request->vat_total ? $request->vat_total : $request->vat_total = 0;
            $output->discount_type          = $request->discount_type ? $request->discount_type : $request->discount_type = 0;
            $output->discount_total         = $request->discount_total;
            $output->rte_fuente             = $request->rte_fuente;
            $output->rte_fuente_total       = $request->rte_fuente_total;
            $output->total_invoice          = $request->total_invoice;
            $output->name                   = $request->name;
            $output->nit_c                    = $request->nit;
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
                $producs_items["estatus"]     = "Remitido";

                ImplanteReemisionesItem::create($producs_items);

                TechnicalReceptionProductoImplante::where('serial', $request["serial"][$key])->update(["estatus" => "Remitido"]);
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
          
        try {
            isset($request["reissue"])  ? $request["reissue"] = 1 : $request["reissue"] = 0;
            $update =  ImplanteReemision::find($remision);
            $update->warehouse              = $request->warehouse;
            $update->id_client              = $request->id_client;
            $update->reissue                = $request->reissue;
            $update->subtotal               = $request->total_invoice;
            $update->subtotal_with_discount = $request->subtotal_with_discount;
            $update->vat_total              = $request->vat_total ? $request->vat_total : $request->vat_total = 0;
            $update->discount_type          = $request->discount_type ? $request->discount_type : $request->discount_type = 0;
            $update->discount_total         = $request->discount_total;
            $update->rte_fuente             = $request->rte_fuente;
            $update->rte_fuente_total       = $request->rte_fuente_total;
            $update->total_invoice          = $request->total_invoice;
            $update->name                   = $request->name;
            $update->nit_c                  = $request->nit;
            $update->save();

            $remi = ImplanteReemisionesItem::where("id_implante_reemision", $remision)->get();

            foreach ($remi as $key => $value) {

                TechnicalReceptionProductoImplante::where('serial', $value["serial"])->where('estatus','Remitido')->update(["estatus" => "Disponible"]);
                ImplanteReemisionesItem::where("serial",$value["serial"])->delete();
            }

            ImplanteReemisionesItem::where("id_implante_reemision", $remision)->delete();
            if (isset($request["referencia"])) {
                foreach ($request->referencia as $key => $value) {
                    $producs_items = [];
                    $producs_items["id_implante_reemision"] = $update->id;
                    $producs_items["referencia"]  = $value;
                    $producs_items["serial"]      = $request["serial"][$key];
                    $producs_items["qty"]         = $request["qty"][$key];
                    $producs_items["price"]       = str_replace(",", "", $request["total"][$key]);
                    $producs_items["vat"]         = 0;
                    $producs_items["total"]       = str_replace(",", "", $request->total_invoice);
                    $producs_items["estatus"]     = "Remitido";
                    TechnicalReceptionProductoImplante::where('serial', $request["serial"][$key])->where('estatus','Disponible')->update(["estatus" => "Remitido"]);
                    ImplanteReemisionesItem::create($producs_items);
                }
            }
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
    public function ListImplanteRemision($id)
    {
        if ($id == "Administrador") {
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

        if ($id == "Silimed_Cali") {
            $data = ImplanteReemision::select("implantes_reemisiones.*", "implantes_clients.name as name_client", "auditoria.*", "user_registro.email as email_regis")
                ->join("auditoria", "auditoria.cod_reg", "=", "implantes_reemisiones.id")
                ->join("implantes_clients", "implantes_clients.id", "=", "implantes_reemisiones.id_client")
                ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
                ->where("auditoria.tabla", "implantes_remision")
                ->where("implantes_reemisiones.warehouse", "Cali")
                ->where("auditoria.status", "!=", "0")
                ->orderBy("implantes_reemisiones.id", "DESC")
                ->with("items")
                ->get();
            return response()->json($data)->setStatusCode(200);
        }

        if ($id == "Silimed_Bog") {
            $data = ImplanteReemision::select("implantes_reemisiones.*", "implantes_clients.name as name_client", "auditoria.*", "user_registro.email as email_regis")
                ->join("auditoria", "auditoria.cod_reg", "=", "implantes_reemisiones.id")
                ->join("implantes_clients", "implantes_clients.id", "=", "implantes_reemisiones.id_client")
                ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
                ->where("auditoria.tabla", "implantes_remision")
                ->where("implantes_reemisiones.warehouse", "Bogota")
                ->where("auditoria.status", "!=", "0")
                ->orderBy("implantes_reemisiones.id", "DESC")
                ->with("items")
                ->get();
            return response()->json($data)->setStatusCode(200);
        }
    }
    public function CreateImplanteOutput(Request $request)
    {
        try {
            // dd($request->all());
            isset($request["reissue"])  ? $request["reissue"] = 1 : $request["reissue"] = 0;
            $output = new ImplantOutput;
            $output->warehouse              = $request->warehouse;
            $output->id_client              = 0;
            $output->reissue                = $request->reissue;
            $output->subtotal               = $request->total_invoice;
            $output->subtotal_with_discount = $request->subtotal_with_discount;
            $output->vat_total              = $request->vat_total ? $request->vat_total : $request->vat_total = 0;
            $output->discount_type          = $request->discount_type ? $request->discount_type : $request->discount_type = 0;
            $output->discount_total         = $request->discount_total;
            $output->rte_fuente             = $request->rte_fuente;
            $output->rte_fuente_total       = $request->rte_fuente_total;
            $output->total_invoice          = $request->total_invoice;
            $output->name                   = $request->name;
            $output->nit_c                  = $request->nit;
            $output->phone                  = $request->phone;
            $output->email                  = $request->email;
            $output->address                = $request->address;
            $output->city                   = $request->city;
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
                    $producs_items["estatus"]     = "Vendido";
                    ImplantOutputItems::create($producs_items);

                    TechnicalReceptionProductoImplante::where('serial', $request["serial"][$key])->update(["estatus" => "Vendido"]);
                   
                    ImplanteReemisionesItem::where("serial", $request["serial"][$key])->update(["estatus" => "Vendido"]);
                    
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
        $update->vat_total              = $request->vat_total ? $request->vat_total : $request->vat_total = 0;
        $update->discount_type          = $request->discount_type ? $request->discount_type : $request->discount_type = 0;
        $update->discount_total         = $request->discount_total;
        $update->rte_fuente             = $request->rte_fuente;
        $update->rte_fuente_total       = $request->rte_fuente_total;
        $update->total_invoice          = $request->total_invoice;
        $update->name                   = $request->name;
        $update->nit_c                  = $request->nit;
        $update->phone                  = $request->phone;
        $update->email                  = $request->email;
        $update->address                = $request->address;
        $update->city                   = $request->city;
        $update->save();

        $out = ImplantOutputItems::where("id_implant_output", $output)->get();

        foreach ($out as $key => $value) {

            TechnicalReceptionProductoImplante::where('serial', $value["serial"])->update(["estatus" => "Disponible"]);
            ImplantOutputItems::where("serial",$value["serial"])->delete();
        }

        if (isset($request->referencia)) {
            foreach ($request['referencia'] as $key => $value) {
                $producs_items = [];
                $producs_items["id_implant_output"]  = $update->id;
                $producs_items["referencia"]    = $value;
                $producs_items["serial"]        = $request["serial"][$key];
                $producs_items["qty"]           = $request["qty"][$key];
                $producs_items["price"]         = str_replace(",", "", $request["total"][$key]);
                $producs_items["vat"]           = 0;
                $producs_items["total"]         = str_replace(",", "", $request->total_invoice);
                $producs_items["estatus"]     = "Vendido";

                TechnicalReceptionProductoImplante::where('serial',$request["serial"][$key])->update(["estatus" => "Vendido"]);
                ImplanteReemisionesItem::where("serial", $request["serial"][$key])->update(["estatus" => "Vendido"]);
                ImplantOutputItems::create($producs_items);
            }
        }
        if ($update) {
            $data = array('mensagge' => "Los datos fueron editados satisfactoriamente");
            return response()->json($data)->setStatusCode(200);
        } else {
            return response()->json("A ocurrido un error")->setStatusCode(400);
        }
    }
    public function ListImplanteOutput($id)
    {
        if ($id == "Administrador") {
            $data = ImplantOutput::select("implantes_output.*", "auditoria.*", "user_registro.email as email_regis")
                ->join("auditoria", "auditoria.cod_reg", "=", "implantes_output.id")
                ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
                ->where("auditoria.tabla", "implantes_output")
                ->where("auditoria.status", "!=", "0")
                ->orderBy("implantes_output.id", "DESC")
                ->with("items")
                ->get();
        }
        if ($id == "Silimed_Cali") {
            $data = ImplantOutput::select("implantes_output.*","auditoria.*", "user_registro.email as email_regis")
                ->join("auditoria", "auditoria.cod_reg", "=", "implantes_output.id")
                ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
                ->where("auditoria.tabla", "implantes_output")
                ->where("implantes_output.warehouse", "Cali")
                ->where("auditoria.status", "!=", "0")
                ->orderBy("implantes_output.id", "DESC")
                ->with("items")
                ->get();
        }
        if ($id == "Silimed_Bog") {
            $data = ImplantOutput::select("implantes_output.*","auditoria.*", "user_registro.email as email_regis")
                ->join("auditoria", "auditoria.cod_reg", "=", "implantes_output.id")
                ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
                ->where("auditoria.tabla", "implantes_output")
                ->where("implantes_output.warehouse", "Bogota")
                ->where("auditoria.status", "!=", "0")
                ->orderBy("implantes_output.id", "DESC")
                ->with("items")
                ->get();
        }
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
    public function UpdateHeadRemision($id)
    {
        try {
            ImplanteReemision::whereId($id)->delete();

            $data = array('mensagge' => "Los datos fueron Eliminados satisfactoriamente");
            if ($data) {
                return response()->json($data)->setStatusCode(200);
            } else {
                return response()->json("A ocurrido un error")->setStatusCode(400);
            }
        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function GetImplante($serial)
    {
        try {
            // dd($serial);
            $data = TechnicalReceptionProductoImplante::select("technical_reception_implante.*","technical_reception_products_implante.*")
            ->join("technical_reception_implante", "technical_reception_products_implante.id_technical_reception_implante","technical_reception_implante.id")
            ->where('technical_reception_products_implante.serial',$serial)
            ->where('technical_reception_products_implante.estatus','!=','Vendido')
            ->orderBy("technical_reception_implante.created_at","DESC")
            ->get();
                    // dd($data);
            $data->map(function ($item) {
                $item->products = ProductImplantes::where('referencia', $item->referencia)->first();
                return $item;
            });

            if (sizeof($data) > 0) {
                return response()->json($data[0])->setStatusCode(200);
            } else {
                return response()->json(["mensaje" => "No Existe el serial $serial"])->setStatusCode(400);
            }
        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function ListClienteImplanteOutput($id)
    {
        // dd($id);
        if ($id == "Administrador") {
            $data = ImplantOutputItems::select("implantes_output_items.*",
            "implantes_output.name",
            "implantes_output.nit_c",
            "implantes_output.warehouse",
            "implantes_output.total_invoice",
            "auditoria.*",
            "user_registro.email as email_regis"
             )
            ->join("implantes_output", "implantes_output_items.id_implant_output", "=", "implantes_output.id")    
            ->join("auditoria", "auditoria.cod_reg", "=", "implantes_output.id")
                ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
                ->where("auditoria.tabla", "implantes_output")
                ->where("auditoria.status", "!=", "0")
                ->orderBy("implantes_output.id", "DESC")
                ->get();
        }
        if ($id == "Silimed_Cali") {
            $data = ImplantOutputItems::select("implantes_output_items.*",
            "implantes_output.name",
            "implantes_output.warehouse",
            "implantes_output.total_invoice",
            "auditoria.*",
            "user_registro.email as email_regis"
             )
            ->join("implantes_output", "implantes_output_items.id_implant_output", "=", "implantes_output.id")    
            ->join("auditoria", "auditoria.cod_reg", "=", "implantes_output.id")
                ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
                ->where("auditoria.tabla", "implantes_output")
                ->where("implantes_output.warehouse", "Cali")
                ->where("auditoria.status", "!=", "0")
                ->orderBy("implantes_output.id", "DESC")
                ->get();
        }
        if ($id == "Silimed_Bog") {
            $data = ImplantOutputItems::select("implantes_output_items.*",
            "implantes_output.name",
            "implantes_output.warehouse",
            "implantes_output.total_invoice",
            "auditoria.*",
            "user_registro.email as email_regis"
             )
            ->join("implantes_output", "implantes_output_items.id_implant_output", "=", "implantes_output.id")    
            ->join("auditoria", "auditoria.cod_reg", "=", "implantes_output.id")
                ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
                ->where("auditoria.tabla", "implantes_output")
                ->where("implantes_output.warehouse", "Bogota")
                ->where("auditoria.status", "!=", "0")
                ->orderBy("implantes_output.id", "DESC")
                ->get();
        }
        return $data;
    }
    public function ListImplanteTrazabilidad($id)
    {
        try {
            // dd($id);
            if ($id == "Administrador") {
                $data = TechnicalReceptionProductoImplante::select(
                    "technical_reception_products_implante.referencia",
                    "technical_reception_products_implante.serial",
                    "technical_reception_products_implante.estatus",
                    "products_implantes.description",
                    "technical_reception_products_implante.id_technical_reception_implante",
                    "implantes_reemisiones_items.id_implante_reemision",
                    "implantes_output_items.id_implant_output",
                    )
                    ->leftJoin("technical_reception_implante", "technical_reception_products_implante.id_technical_reception_implante","technical_reception_implante.id")
                    ->leftJoin("products_implantes", "technical_reception_products_implante.referencia","products_implantes.referencia")
                    ->leftJoin("implantes_output_items","technical_reception_products_implante.serial","implantes_output_items.serial")
                    ->leftJoin("implantes_reemisiones_items","technical_reception_products_implante.serial","implantes_reemisiones_items.serial")
                    ->with('head:id,warehouse,created_at')
                    ->get();
            }
            if ($id == "Silimed_Cali") {

                // $data = TechnicalReceptionProductoImplante::select("technical_reception_implante.*","technical_reception_products_implante.*","products_implantes.*")
                //     ->join("technical_reception_implante", "technical_reception_products_implante.id_technical_reception_implante","technical_reception_implante.id")
                //     ->join("products_implantes", "technical_reception_products_implante.referencia","products_implantes.referencia")
                //     ->where("technical_reception_implante.warehouse", "Cali")                   
                //     ->get();

                    $data = TechnicalReceptionProductoImplante::select(
                        "technical_reception_products_implante.referencia",
                        "technical_reception_products_implante.serial",
                        "technical_reception_products_implante.estatus",
                        "products_implantes.description",
                        "technical_reception_products_implante.id_technical_reception_implante",
                        "implantes_reemisiones_items.id_implante_reemision",
                        "implantes_output_items.id_implant_output",
                        )
                        ->leftJoin("technical_reception_implante", "technical_reception_products_implante.id_technical_reception_implante","technical_reception_implante.id")
                        ->leftJoin("products_implantes", "technical_reception_products_implante.referencia","products_implantes.referencia")
                        ->leftJoin("implantes_output_items","technical_reception_products_implante.serial","implantes_output_items.serial")
                        ->leftJoin("implantes_reemisiones_items","technical_reception_products_implante.serial","implantes_reemisiones_items.serial")
                        ->with('head:id,warehouse,created_at')
                        ->where("technical_reception_implante.warehouse", "Cali")
                        ->get();
                



            }
            if ($id == "Silimed_Bog") {
                
                // $data = TechnicalReceptionProductoImplante::select("technical_reception_implante.*","technical_reception_products_implante.*","products_implantes.*")
                //     ->join("technical_reception_implante", "technical_reception_products_implante.id_technical_reception_implante","technical_reception_implante.id")
                //     ->join("products_implantes", "technical_reception_products_implante.referencia","products_implantes.referencia")
                //     ->where("technical_reception_implante.warehouse", "Bogota")                   
                //     ->get();

                $data = TechnicalReceptionProductoImplante::select(
                    "technical_reception_products_implante.referencia",
                    "technical_reception_products_implante.serial",
                    "technical_reception_products_implante.estatus",
                    "products_implantes.description",
                    "technical_reception_products_implante.id_technical_reception_implante",
                    "implantes_reemisiones_items.id_implante_reemision",
                    "implantes_output_items.id_implant_output",
                    )
                    ->leftJoin("technical_reception_implante", "technical_reception_products_implante.id_technical_reception_implante","technical_reception_implante.id")
                    ->leftJoin("products_implantes", "technical_reception_products_implante.referencia","products_implantes.referencia")
                    ->leftJoin("implantes_output_items","technical_reception_products_implante.serial","implantes_output_items.serial")
                    ->leftJoin("implantes_reemisiones_items","technical_reception_products_implante.serial","implantes_reemisiones_items.serial")
                    ->with('head:id,warehouse,created_at')
                    ->where("technical_reception_implante.warehouse", "Bogota")
                    ->get();
            }
            return $data;
        } catch (\Throwable $th) {
            return $th;
        }
    }
}

