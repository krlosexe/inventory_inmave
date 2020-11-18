<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TechnicalReceptionProductoImplante;

class ImplantesController extends Controller
{
    public function GetExistenceImplante($serial)
    {
        try {
            $data = TechnicalReceptionProductoImplante::where('serial',$serial)->get();
            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
