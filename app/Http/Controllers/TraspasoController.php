<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{ProductsTrapase,ProductsEntry,ProductsEntryItems};


class TraspasoController extends Controller
{
    public function createOuptTraspase(Request $request)
    {
        try {
        //  dd($request->all());
            foreach ($request->id_product as $key => $value) {
                
                $traspase = [];
                $traspase['id_product'] = $value;
                $traspase['qty'] = $request['qty'][$key];
                $traspase['type'] = $request->movimiento;
                $traspase['origin'] = $request->warehouse;
                $traspase['destiny'] = $request->destiny;
                $traspase['id_user'] = $request->id_user;               
                $traspaso = ProductsTrapase::create($traspase);
            
            }

            foreach($request["id_product"] as $key => $value){
                
                $producs_entry = [];
                $producs_entry['warehouse'] = $request->warehouse;
                $producs_entry['number_invoice'] = 0;
                $producs_entry['taxes'] = 0;
                $producs_entry['transport']  = 0;
                $producs_entry['id_traspase'] = $traspaso->id;
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
}
