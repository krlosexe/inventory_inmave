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
    public function GetAlmacen($factura,$almacen)
    {
        try {

            $data = Products::select('products.*','product_entry_items.price','products_entry.warehouse','product_output.id as fact','product_output_items.price as precio_venta')
            ->leftJoin('product_entry_items','products.id','product_entry_items.id_product')
            ->leftJoin('products_entry','product_entry_items.id_entry','products_entry.id')
            ->leftJoin('product_output_items','products.id','product_output_items.id_product')
            ->leftJoin('product_output','product_output_items.id_output','product_output.id')
            ->where('products_entry.warehouse',$almacen)
            ->Where('product_output.id',$factura)
            ->groupBy('products.id')
            ->withCount('total_productos')
            ->get();

            $data->map(function($item) use($almacen){

                    // dd($item->price_distributor_x_caja,$item->price_cop);
                    $dx_caja = $item->price_distributor_x_caja - $item->price_cop;
                    $item->dx_caja = number_format($dx_caja,2, ',', '.');

                    $dx_vial = $item->price_distributor_x_vial - $item->price_cop; 
                    $item->dx_vial = number_format($dx_vial,2, ',', '.');

                    $cx_caja = $item->price_cliente_x_caja - $item->price_cop; 
                    $item->cx_caja = number_format($cx_caja,2, ',', '.');

                    $cx_vial = $item->price_cliente_x_vial - $item->price_cop;
                    $item->cx_vial = number_format($cx_vial,2, ',', '.'); 
                    
                    
                    // ProductusOutputItems::select('product_output_items.*')
                    // ->leftJoin('product_output','product_output_items.id_output','product_output.id')
                    // ->leftJoin('products','product_output_items.id_product','products.id')
                    // ->where('product_output.warehouse',$almacen)
                    // ->where('products.price_cop', $item->price_distributor_x_caja)
                    // ->where('product_output_items.id_product', $item->id)->first();
                    // ->sum('product_output_items.total');
                    // if($dx_caja){
                        // $item->dx_caja ? $dx_caja->price : 0 ;
                    // }

                // $dx_vial = ProductusOutputItems::select('product_output_items.*')
                //     ->leftJoin('product_output','product_output_items.id_output','product_output.id')
                //     ->leftJoin('products','product_output_items.id_product','products.id')
                //     ->where('product_output.warehouse',$almacen)
                //     ->where('products.price_cop', $item->price_distributor_x_vial)
                //     ->where('product_output_items.id_product', $item->id)->first();
                //     // ->sum('product_output_items.total');
                //     // if($dx_vial){
                //         $item->dx_vial ? $dx_vial->price : 0 ;
                //     // }


                // $cx_caja = ProductusOutputItems::select('product_output_items.*')
                //     ->leftJoin('product_output','product_output_items.id_output','product_output.id')
                //     ->leftJoin('products','product_output_items.id_product','products.id')
                //     ->where('product_output.warehouse',$almacen)
                //     ->where('products.price_cop', $item->price_cliente_x_caja)
                //     ->where('product_output_items.id_product', $item->id)->first();
                //     // ->sum('product_output_items.total');
                //     // if($cx_caja){
                //         $item->cx_caja ? $cx_caja->price : 0 ;
                //     // }

                //  $cx_vial = ProductusOutputItems::select('product_output_items.*')
                //     ->leftJoin('product_output','product_output_items.id_output','product_output.id')
                //     ->leftJoin('products','product_output_items.id_product','products.id')
                //     ->where('product_output.warehouse',$almacen)
                //     ->where('products.price_cop', $item->price_cliente_x_vial)
                //     ->where('product_output_items.id_product', $item->id)->first();
                //     // ->sum('product_output_items.total');
                //     //  if($cx_vial){
                //        $item->cx_vial ?  $cx_vial->price : 0;
                //     // }

                //   $item->cx_vial_ganacia =  $item->precio_venta - cx_vial

        
                $item->qty_total = ProductsEntryItems::where('product_entry_items.id_product',$item->id)
                    ->leftJoin('products_entry','product_entry_items.id_entry','products_entry.id')
                    ->where('products_entry.warehouse',$almacen)
                    ->sum('product_entry_items.qty');

                // $item->qty_total_vendido = ProductusOutputItems::where('product_output_items.id_product',$item->id)
                //     ->leftJoin('product_output','product_output_items.id_output','product_output.id')
                //     ->where('product_output.warehouse',$almacen)
                //     ->sum('product_output_items.total');


                // $item->qty_salida = ProductusOutputItems::where('product_output_items.id_product',$item->id)

                //     ->leftJoin('product_output','product_output_items.id_output','product_output.id')
                //     ->where('product_output.warehouse',$almacen)
                //     ->sum('product_output_items.qty');

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
