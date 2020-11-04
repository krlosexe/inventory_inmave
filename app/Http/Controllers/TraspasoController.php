<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{ProductsTrapase,ProductsEntry,ProductsEntryItems,ProductsOutputTraspase,ProductusOutputItemsTraspase};


class TraspasoController extends Controller
{
    public function createOuptTraspase(Request $request)
    {
        // dd($request->all());
        try {
            foreach ($request->id_product as $key => $value) {
                
                $traspase = [];
                $traspase['id_product'] = $value;
                $traspase['qty'] = $request['qty'][$key];
                $traspase['type'] = "Traspaso";
                $traspase['origin'] = $request->warehouse;
                $traspase['destiny'] = $request->destiny;
                $traspase['id_user'] = $request->id_user;               
                $traspaso = ProductsTrapase::create($traspase);
            
            }

            foreach($request["id_product"] as $key => $value){
                
                $producs_output = [];
                $producs_output['warehouse'] = $request->warehouse;
                $producs_output['id_traspase'] = $traspaso->id;
                $salida =  ProductsOutputTraspase::create($producs_output);
              
                
            }   

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
    public function ListOuptTraspase ()
    {
        try {

            $data = ProductsTrapase::select(
                'product_trapase.id',
                'product_trapase.id_product',
                'product_trapase.type',
                // 'product_trapase.qty',
                'product_trapase.origin',
                'product_trapase.destiny',
                // 'products.code',
                // 'products.description',
                // 'product_entry_items.lote',
                'product_entry_items.price',
                'users.email',
                'product_trapase.created_at'
            )
            ->Join('product_output_traspase','product_trapase.id','product_output_traspase.id_traspase')
            ->Join('products_entry','product_trapase.id','products_entry.id_traspase')
            ->Join('product_entry_items','products_entry.id','product_entry_items.id_entry')
            ->Join('products','product_trapase.id_product','products.id')
            ->Join('users','product_trapase.id_user','users.id')
            ->get();


           

            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function ListOuptTraspaseById($id)
    {
        try {
            $data = ProductsTrapase::where('product_trapase.id_product',$id)->with('product')->get();

            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
