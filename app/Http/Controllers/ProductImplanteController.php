<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductImplantes;

class ProductImplanteController extends Controller
{

    public function ListProductImplante()
    {
        try {
            
            $data = ProductImplantes::with('user')->get();
            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function CreateProductImplante(Request $request)
    {
        try {
            $create = ProductImplantes::create($request->all());
            if($create){
                $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");    
                return response()->json($data)->setStatusCode(200);
            }else{
                return response()->json("A ocurrido un error")->setStatusCode(400);
            }

        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function EditProductImplante(Request $request,$id)
    {
        try {
            $update = ProductImplantes::find($id);
            $update->referencia = $request->referencia;
            $update->register_invima = $request->register_invima;
            $update->description = $request->description;
            $update->gramaje = $request->gramaje;
            $update->perfil = $request->perfil;
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

    public function DeleteProductImplante($id)
    {
        try {
            ProductImplantes::where('id',$id)->delete();
            $data = array('mensagge' => "Los datos fueron eliminados satisfactoriamente");    
            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
    }

}
