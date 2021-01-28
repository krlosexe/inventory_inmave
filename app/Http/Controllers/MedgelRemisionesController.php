<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{MedgelTechnicalReceptionItem,MedgelTechnicalReception,MedgelReemision,MedgelProduct};

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
            $data =  MedgelTechnicalReception::orderBy('created_at','ASC')->get();
            $data->map(function($item)use($lote){
                $item->head = MedgelTechnicalReceptionItem::where('lote', $lote) ->where('id',$item->id_medgel_technical_reception)
                
                ->first();
                $item->product = MedgelProduct::where('id',$item->id_product)->first();
                return $item;
            });
            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
