<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MedgelProduct;

class MedgelProductsControler extends Controller
{
    public function ListProductMedgel()
    {
        try {
            $data = MedgelProduct::with('user')->get();
            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function CreateProductMedgel(Request $request)
    {
        try {
            $create = MedgelProduct::create($request->all());
            if ($create) {
                $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
                return response()->json($data)->setStatusCode(200);
            } else {
                return response()->json("A ocurrido un error")->setStatusCode(400);
            }
        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function EditProductMedgel(Request $request, $id)
    {
        try {
            $update = MedgelProduct::find($id);
            $update->referencia = $request->referencia;
            $update->lote = $request->lote;
            $update->qty = $request->qty;
            $update->date_expire = $request->date_expire;
            $update->register_invima = $request->register_invima;
            $update->description = $request->description;
            $update->id_user = $request->id_user;
            $update->save();

            if ($update) {
                $data = array('mensagge' => "Los datos fueron editados satisfactoriamente");    
                return response()->json($data)->setStatusCode(200);
            }else{
                return response()->json("A ocurrido un error")->setStatusCode(400);
            }
        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function DeleteProductMedgel($id)
    {
        try {
            MedgelProduct::where('id',$id)->delete();
            $data = array('mensagge' => "Los datos fueron eliminados satisfactoriamente");    
            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
