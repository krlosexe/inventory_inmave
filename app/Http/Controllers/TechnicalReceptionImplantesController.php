<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{TechnicalReceptionImplante,TechnicalReceptionProductoImplante};

class TechnicalReceptionImplantesController extends Controller
{
    public function CreateTechnicalReceptionImplante(Request $request)
    {
        // dd($request->all());
        try {
            $tri = new TechnicalReceptionImplante;
            $tri->id_provider = $request->id_provider;
            $tri->id_user = $request->id_user;
            $tri->save();

            foreach($request["referencia"] as $key => $referencia){

                $products = [];
                $products["id_technical_reception_implante"]  = $tri->id;
                $products["referencia"]              = $referencia;
                $products["serial"]                  = $request["serial"][$key];
                $products["lote"]                    = $request["lotes"][$key];
                $products["register_invima"]         = $request["register_invima"][$key];
                $products["date_expiration"]         = $request["date_expiration"][$key];
                $products["price"]                   = str_replace(",", "", $request["price"][$key]);
                $products["gramaje"]                 = $request["gramaje"][$key];
                $products["perfil"]                 = $request["perfil"][$key];
    
                TechnicalReceptionProductoImplante::create($products);
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
            $update = TechnicalReceptionImplante::find($technicalReception);
            $update->id_provider = $request->id_provider;
            $update->total_invoice = $request->total;
            $update->id_user = $request->id_user;
            $update->save();

            TechnicalReceptionProductoImplante::where("id_technical_reception_implante", $technicalReception)->delete();
    
            foreach($request["serial"] as $key => $serial){
    
                    $products = [];
                    $products["id_technical_reception_implante"]  = $technicalReception;
                    $products["serial"]                  = $serial;
                    $products["lote"]                    = $request["lotes"][$key];
                    $products["register_invima"]         = $request["register_invima"][$key];
                    $products["date_expiration"]         = $request["date_expiration"][$key];
                    $products["price"]                   = str_replace(",", "", $request["price"][$key]);
                    $products["gramaje"]                 = $request["gramaje"][$key];
                    $products["perfil"]                  = $request["perfil"][$key];
        
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

    public function ListTechnicalReceptionImplante()
    {
        try {
            $data = TechnicalReceptionImplante::
            with('detalle')
            ->with('proveedor')
            ->with('user')
            ->get();

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
