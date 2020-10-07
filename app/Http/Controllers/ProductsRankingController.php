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
            $users = DB::table('product_output_items')
            ->select('product_output_items.*',
                DB::raw('SUM(product_output_items.total) as total'),
                DB::raw('SUM(product_output_items.qty) as quantities')
            )
            ->leftJoin('product_output','product_output_items.id_output','product_output.id')
            ->groupBy('product_output_items.id_output')
            ->sum('product_output_items.qty')
            ->get();
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
