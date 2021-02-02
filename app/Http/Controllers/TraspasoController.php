<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{
    ProductsEntry,
    ProductsEntryItems,
    ProductsOutputTraspase,
    ProductusOutputItemsTraspase,
    ImplanteProductOutputTraspase,
    ImplanteProductOutputItemTraspase,
    TechnicalReceptionImplante,
    TechnicalReceptionProductoImplante
};


class TraspasoController extends Controller
{
    public function createOuptTraspase(Request $request)
    {
        try {
                $producs_output = [];
                $producs_output['warehouse'] = $request->warehouse;
                $producs_output['destiny'] = $request->destiny;
                $producs_output['id_user'] = $request->id_user;  
                $producs_output['type'] = "Traspaso";     
                $salida =  ProductsOutputTraspase::create($producs_output);
              
            
            foreach($request["id_product"] as $key => $value){

                $producs_item_out = [];
                $producs_item_out["id_output_traspase"]   = $salida->id;
                $producs_item_out["id_product"]  = $value;
                $producs_item_out["qty"]         = $request["qty"][$key];
                
                ProductusOutputItemsTraspase::create($producs_item_out);
                
            } 

            foreach($request["id_product"] as $key => $value){
                
                $producs_entry = [];
                $producs_entry['warehouse'] = $request->destiny;
                $producs_entry['number_invoice'] = 0;
                $producs_entry['taxes'] = 0;
                $producs_entry['transport']  = 0;
                $entrada =  ProductsEntry::create($producs_entry);
              
                
            }   

            foreach($request["id_product"] as $key => $value){

                $producs_items = [];
                $producs_items["id_entry"]          = $entrada->id;
                $producs_items["id_product"]        = $value;
                $producs_items["lote"]              = $request["lote"][$key];
                $producs_items["register_invima"]   = $request["register_invima"][$key];
                $producs_items["date_expiration"]   = $request["date_expiration"][$key];
                $producs_items["qty"]               = $request["qty"][$key];
                $producs_items["price"]             = str_replace(",", "", $request["price_euro"][$key]);
                $producs_items["total"]             = str_replace(",", "", $request["ext_total"][$key]);
                
                ProductsEntryItems::create($producs_items);
                
            } 

            $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");    
            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function ListOuptTraspase ()
    {
        try {

            $data = ProductsOutputTraspase::with('usuario')->get();
    
            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function ListOuptTraspaseById($id)
    {
        try {
            $data = ProductusOutputItemsTraspase::where('id_output_traspase',$id)->with('product')->get();

            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function createImplanteOuptTraspase(Request $request)
    {
        try {
                $producs_output = [];
                $producs_output['warehouse'] = $request->warehouse;
                $producs_output['destiny']   = $request->destiny;
                $producs_output['id_user']   = $request->id_user;  
                $producs_output['type']      = "Traspaso";     
                $salida =  ImplanteProductOutputTraspase::create($producs_output);
              
            
            foreach($request["id_product"] as $key => $value){
                $producs_item_out = [];
                $producs_item_out["id_output_traspase"]   = $salida->id;
                $producs_item_out["id_product"]           = $value;
                $producs_item_out["referencia"]           = $value['referencia'];
                $producs_item_out["serial"]               = $value['serial'];
                $producs_item_out["qty"]                  = $request["qty"][$key];
                
                ImplanteProductOutputItemTraspase::create($producs_item_out);
                
            } 

            foreach($request["referencia"] as $key => $value){
             
                $producs_entry['warehouse']     = $request->destiny;
                $producs_entry['id_provider']   = 0;
                $producs_entry['fecha_ingreso'] = $request->date("Y-m-d");
                $producs_entry['bodega_origen'] = $request->warehouse;
                $producs_entry['nro_traslado']  = $request->$salida->id;
                $producs_entry['id_user']       = $request->id_user;
               
                $entrada =  TechnicalReceptionImplante::create($producs_entry);
              
            }   
            foreach($request["referencia"] as $key => $referencia){
                $products = [];
                $products["id_technical_reception_implante"]  = $entrada->id;
                $products["referencia"]              = $referencia;
                $products["serial"]                  = $request["serial"][$key];
                $products["id_product"]              = $request["id_product"][$key];
                $products["lote"]                    = $request["lotes"][$key];
                $products["register_invima"]         = $request["register_invima"][$key];
                $products["date_expiration"]         = $request["date_expiration"][$key];
                $products["description"]             = $request["description"][$key];
                $products["gramaje"]                 = $request["gramaje"][$key];
                $products["perfil"]                  = $request["perfil"][$key];
                $products["estatus"]                 = "Disponible";

                TechnicalReceptionProductoImplante::create($products);

            }

            $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");    
            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function ListImplanteOuptTraspase()
    {
        try {
            $data = ImplanteProductOutputTraspase::with('usuario')->get();
            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function ListImplanteOuptTraspaseById($id)
    {
        try {
            $data = ImplanteProductOutputItemTraspase::where('id_implante_output_traspase',$id)->with('product')->get();
            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
