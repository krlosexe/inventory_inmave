<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{MedgelTechnicalReception,MedgelTechnicalReceptionItem,Auditoria,MedgelProduct};

class TechnicalReceptionMedgelController extends Controller
{
    public function CreateTechnicalReceptionMedgel(Request $request)
    {
        try {
            
            // dd($request->all());

            $tri = new MedgelTechnicalReception;
            $tri->warehouse   = $request->warehouse;
            $tri->id_provider = $request->id_provider;
            $tri->id_user = $request->id_user;
            $tri->date_expiration = $request->date_expiration;
            $tri->save();

            foreach($request["id_product"] as $key => $product){
                $products = [];
                $products["id_medgel_technical_reception"]  = $tri->id;
                $products["id_product"]              = $product;              
                $products["lote"]                    = $request["lote"][$key];
                $products["qty"]                     = $request["qty"][$key];
                $products["price"]                   = str_replace(",", "", $request["price"][$key]);
              
                $create = MedgelTechnicalReceptionItem::create($products);

                $auditoria              = new Auditoria;
                $auditoria->tabla       = "medgel_technical_reception";
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
    public function EditarTechnicalReceptionMedgel(Request $request, $technicalReception)
    {
        try {
            // dd($request->all());     
            $update = MedgelTechnicalReception::find($technicalReception);
            $update->warehouse   = $request->warehouse;
            $update->id_provider = $request->id_provider;
            $update->id_user = $request->id_user;
            $update->date_expiration = $request->date_expiration;
            $update->save();

            MedgelTechnicalReceptionItem::where("id_medgel_technical_reception", $technicalReception)->delete();
    
            foreach($request["id_product"] as $key => $product){
                    $products = [];
                    $products["id_medgel_technical_reception"]  = $update->id;
                    $products["id_product"]                     = $product;
                    $products["lote"]                           = $request["lote"][$key];
                    $products["qty"]                            = $request["qty"][$key];
                    $products["price"]                          = str_replace(",", "", $request["price"][$key]);                  
                    MedgelTechnicalReceptionItem::create($products);
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

    public function ListTechnicalReceptionMedgel()
    {
        try {
            $data = MedgelTechnicalReception::
            with('proveedor')
            ->with('user')
            ->get();
            $data->map(function($item){
                $item->detalle = MedgelTechnicalReceptionItem::where('id_medgel_technical_reception',$item->id)->first();
                $item->product = MedgelProduct::where('id', $item->detalle->id_product)->first();
                return $item;
            });
            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function DeleteTechnicalReceptionMedgel($id)
    {
        try {
            MedgelTechnicalReception::where('id',$id)->delete();
            MedgelTechnicalReceptionItem::where('id_technical_reception_implante',$id)->delete();
            $data = array('mensagge' => "Los datos fueron eliminados satisfactoriamente");    
            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function searchReferencia($referencia)
    {
        try {
            $data = MedgelProduct::where('referencia', $referencia)->first();
            if ($data) {
                return response()->json($data)->setStatusCode(200);
            } else {
                return ["mensaje" => "No Existe la referencia $referencia "];
            }
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
