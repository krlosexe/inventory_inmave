<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Products, 
         ProductsEntryItems,
         ProductusOutputItems,
         ReemisionesItems
        };

class AlmacenController extends Controller
{
    public function GetAlmacen($almacen)
    {
        try {
            $data = Products::select('products.id','products.code','products.description','product_entry_items.price','products_entry.warehouse')
            ->leftJoin('product_entry_items','products.id','product_entry_items.id_product')
            ->leftJoin('products_entry','product_entry_items.id_entry','products_entry.id')
            ->where('products_entry.warehouse',$almacen)
            ->groupBy('products.id')
            ->withCount('total_productos')
            ->get();
            
            $data->map(function($item){

                $item->qty_total = ProductsEntryItems::where('id_product',$item->id)->sum('qty');
                $item->qty_total_vendido = ProductusOutputItems::where('id_product',$item->id)->sum('total');
                $item->qty_salida = ProductusOutputItems::where('id_product',$item->id)->count('qty');
                $item->remision_total = ReemisionesItems::where('id_product',$item->id)->sum('total');
                $item->qty_remision = ReemisionesItems::where('id_product',$item->id)->count('qty');

                return $item;
            });
            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
        
    }
}
