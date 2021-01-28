<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{MedgelTechnicalReception,MedgelReemision,MedgelProduct};

class MedgelRemisionesController extends Controller
{
    public function ListMedgelRemision()
    {
        $data = MedgelReemision::with('items')->get();
        return response()->json($data)->setStatusCode(200);
    }
    public function GetExistenceMedgel($lote)
    {
        try {
            $data =  MedgelTechnicalReception::with('detalle.product')
            ->orderBy('created_at','ASC')
            ->with('proveedor')
            ->with('user')
            ->first();
           
            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
