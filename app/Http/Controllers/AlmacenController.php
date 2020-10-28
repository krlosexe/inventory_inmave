<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Products, 
         ProductsEntryItems
        };

class AlmacenController extends Controller
{
    public function GetAlmacen($almacen)
    {
        try {
            $data = Products::select('products.id','products.description','product_entry_items.price','products_entry.warehouse')
            ->leftJoin('product_entry_items','products.id','product_entry_items.id_product')
            ->leftJoin('products_entry','product_entry_items.id_entry','products_entry.id')
            ->where('products_entry.warehouse',$almacen)
            ->withCount('total_productos')
            ->get();
            $data->map(function($item){
                $item->qty_total = ProductsEntryItems::where('id_product',$item->id)->sum('qty');
                return $item;
            });
            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
        
    }
}
