<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{TechnicalReceptionImplante,
        TechnicalReceptionProductoImplante,
        Auditoria
    };

class TechnicalReceptionImplantesController extends Controller
{
    public function CreateTechnicalReceptionImplante(Request $request)
    {
        try {
            // dd($request->all());
            $tri = new TechnicalReceptionImplante;
            $tri->warehouse   = $request->warehouse;
            $tri->id_provider = $request->id_provider;
            $tri->fecha_ingreso = $request->fecha_ingreso;
            $tri->bodega_origen = $request->bodega_origen;
            $tri->nro_traslado = $request->nro_traslado;
            $tri->id_user = $request->id_user;
            $tri->save();

            foreach($request["referencia"] as $key => $referencia){
                $products = [];
                $products["id_technical_reception_implante"]  = $tri->id;
                $products["referencia"]              = $referencia;
                $products["serial"]                  = $request["serial"][$key];
                // $products["id_product"]              = $request["id_product"][$key];
                $products["lote"]                    = $request["lotes"][$key];
                $products["register_invima"]         = $request["register_invima"][$key];
                $products["date_expiration"]         = $request["date_expiration"][$key];
                // $products["price"]                   = str_replace(",", "", $request["price"][$key]);
                $products["description"]             = $request["description"][$key];
                $products["gramaje"]                 = $request["gramaje"][$key];
                $products["perfil"]                  = $request["perfil"][$key];
                $products["estatus"]                 = "Disponible";

                $create = TechnicalReceptionProductoImplante::create($products);

                $auditoria              = new Auditoria;
                $auditoria->tabla       = "technical_reception_implante";
                $auditoria->cod_reg     = $create->id;
                $auditoria->status      = 1;
                $auditoria->fec_regins  = date("Y-m-d H:i:s");
                $auditoria->usr_regins  = $request["id_user"];
                $auditoria->save();
            }
            $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");    
            return response()->json($data)->setStatusCode(200);

        } catch (\Throwable $th) {
            return  $th;
        }
    }
    public function EditarTechnicalReceptionImplante(Request $request, $technicalReception)
    {
        try {   
            // dd($request->all());

            $update = TechnicalReceptionImplante::find($technicalReception);
            $update->id_provider = $request->id_provider;
            $update->total_invoice = $request->total;
            $update->fecha_ingreso = $request->fecha_ingreso;
            $update->bodega_origen = $request->bodega_origen;
            $update->nro_traslado = $request->nro_traslado;
            $update->save();
            TechnicalReceptionProductoImplante::where("id_technical_reception_implante", $technicalReception)->delete();
            foreach($request["serial"] as $key => $serial){
    
                    $products = [];
                    $products["id_technical_reception_implante"]  = $update->id;
                    $products["referencia"]              = $request["referencia"][$key];
                    $products["serial"]                  = $serial;
                    // $products["id_product"]              = $request["id_product"][$key];
                    $products["lote"]                    = $request["lote"][$key];
                    $products["register_invima"]         = $request["register_invima"][$key];
                    $products["date_expiration"]         = $request["date_expiration"][$key];
                    // $products["price"]                   = str_replace(",", "", $request["price"][$key]);
                    $products["description"]             = $request["description"][$key];
                    $products["gramaje"]                 = $request["gramaje"][$key];
                    $products["perfil"]                  = $request["perfil"][$key];
                    $products["estatus"]                 = "Disponible";
                TechnicalReceptionProductoImplante::create($products);
            }
            if ($update) {
                $data = array('mensagge' => "Los datos fueron editados satisfactoriamente");    
                return response()->json($data)->setStatusCode(200);
            }else{
                return response()->json("A ocurrido un error")->setStatusCode(400);
            }
        } catch (\Throwable $th) {
            return  $th;
        }
    }
    public function ListTechnicalReceptionImplante($rol)
    {
        try {
            // dd($rol);
            if($rol == "Administrador"){
                $data = TechnicalReceptionImplante::
                 with('proveedor')
                ->with('user')
                // ->with('detalle')
                ->get();
                $data->map(function($item){
                    $item->detalle = TechnicalReceptionProductoImplante::where('id_technical_reception_implante',$item->id)->get();
                  return $item;
                });
            }
            if($rol == "Silimed_Barranquilla"){
                $data = TechnicalReceptionImplante::where('warehouse','Barranquilla')
                ->with('proveedor')
                ->with('user')
                // ->with('detalle')
                ->get();
                $data->map(function($item){
                    $item->detalle = TechnicalReceptionProductoImplante::where('id_technical_reception_implante',$item->id)->get();
                  return $item;
                });
            }

            if($rol == "Silimed_Cali"){
                $data = TechnicalReceptionImplante::where('warehouse','Cali')
                ->with('proveedor')
                ->with('user')
                // ->with('detalle')
                ->get();
                $data->map(function($item){
                    $item->detalle = TechnicalReceptionProductoImplante::where('id_technical_reception_implante',$item->id)->get();
                  return $item;
                });
            }

            if($rol == "Silimed_Bog"){
                $data = TechnicalReceptionImplante::where('warehouse','Bogota')
                ->with('proveedor')
                ->with('user')
                // ->with('detalle')
                ->get();
                $data->map(function($item){
                    $item->detalle = TechnicalReceptionProductoImplante::where('id_technical_reception_implante',$item->id)->get();
                  return $item;
                });
            }
            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function DeleteTechnicalReceptionImplante($id)
    {
        try {
            TechnicalReceptionImplante::where('id',$id)->delete();
            TechnicalReceptionProductoImplante::where('id_technical_reception_implante',$id)->delete();
            $data = array('mensagge' => "Los datos fueron eliminados satisfactoriamente");    
            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
