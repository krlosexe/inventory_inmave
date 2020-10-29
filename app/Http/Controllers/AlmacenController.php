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
            $data = Products::select('products.*','product_entry_items.price','products_entry.warehouse')
            ->leftJoin('product_entry_items','products.id','product_entry_items.id_product')
            ->leftJoin('products_entry','product_entry_items.id_entry','products_entry.id')
            ->where('products_entry.warehouse',$almacen)
            ->groupBy('products.id')
            ->withCount('total_productos')
            ->get();



            $data->map(function($item) use($almacen){


                 $price_distributor_x_caja = $item->price_distributor_x_caja;
                 $price_distributor_x_vial = $item->price_distributor_x_vial;
                 $price_cliente_x_caja  = $item->price_cliente_x_caja;
                 $price_cliente_x_vial  = $item->price_cliente_x_vial;


                $item->dx_caja = ProductusOutputItems::select('product_output_items.*')
                    ->leftJoin('product_output','product_output_items.id_output','product_output.id')
                    ->leftJoin('products','product_output_items.id_product','products.id')
                    ->where('product_output.warehouse',$almacen)
                    ->where('product_output_items.price', $price_distributor_x_caja)
                    ->where('product_output_items.id_product', $item->id)->sum('product_output_items.price');

                $item->dx_vial = ProductusOutputItems::select('product_output_items.*')
                    ->leftJoin('product_output','product_output_items.id_output','product_output.id')
                    ->leftJoin('products','product_output_items.id_product','products.id')
                    ->where('product_output.warehouse',$almacen)
                    ->where('product_output_items.price', $price_distributor_x_vial)
                    ->where('product_output_items.id_product', $item->id)->sum('product_output_items.price');


                $item->cx_caja = ProductusOutputItems::select('product_output_items.*')
                    ->leftJoin('product_output','product_output_items.id_output','product_output.id')
                    ->leftJoin('products','product_output_items.id_product','products.id')
                    ->where('product_output.warehouse',$almacen)
                    ->where('product_output_items.price', $price_cliente_x_caja)
                    ->where('product_output_items.id_product', $item->id)->sum('product_output_items.price');

                $item->cx_vial = ProductusOutputItems::select('product_output_items.*')
                    ->leftJoin('product_output','product_output_items.id_output','product_output.id')
                    ->leftJoin('products','product_output_items.id_product','products.id')
                    ->where('product_output.warehouse',$almacen)
                    ->where('product_output_items.price', $price_cliente_x_vial)
                    ->where('product_output_items.id_product', $item->id)->sum('product_output_items.price');

           
        
                $item->qty_total = ProductsEntryItems::where('product_entry_items.id_product',$item->id)
                ->leftJoin('products_entry','product_entry_items.id_entry','products_entry.id')
                ->where('products_entry.warehouse',$almacen)
                ->sum('product_entry_items.qty');

                $item->qty_total_vendido = ProductusOutputItems::where('product_output_items.id_product',$item->id)
                ->leftJoin('product_output','product_output_items.id_output','product_output.id')
                ->where('product_output.warehouse',$almacen)
                ->sum('product_output_items.total');


                $item->qty_salida = ProductusOutputItems::where('product_output_items.id_product',$item->id)
                ->leftJoin('product_output','product_output_items.id_output','product_output.id')
                ->where('product_output.warehouse',$almacen)
                ->sum('product_output_items.qty');

                $item->remision_total = ReemisionesItems::where('reemisiones_items.id_product',$item->id)
                ->leftJoin('reemisiones','reemisiones_items.id_reemision','reemisiones.id')
                ->where('reemisiones.warehouse',$almacen)
                ->sum('reemisiones_items.total');

                $item->qty_remision = ReemisionesItems::where('reemisiones_items.id_product',$item->id)
                ->leftJoin('reemisiones','reemisiones_items.id_reemision','reemisiones.id')
                ->where('reemisiones.warehouse',$almacen)
                ->sum('reemisiones_items.qty');

                return $item;
            });
            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }

    }
}
