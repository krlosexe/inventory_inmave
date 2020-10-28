<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductusOutput;
use App\ProductusOutputItems;
use DB;

class ProductsRankingController extends Controller
{
    public function listRankinProducts()
    {
        try {            
             $data = DB::table('product_output_items')
            ->select('product_output_items.id_product','products.description',
              DB::raw('SUM(product_output_items.qty) as quantities')
            )
            ->leftJoin('products','product_output_items.id_product','products.id')
            ->groupBy('product_output_items.id_product')
            ->orderBy('quantities','ASC')
            ->get();
            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
