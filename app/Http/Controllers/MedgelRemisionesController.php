<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\{MedgelTechnicalReception,
    MedgelReemision,
    MedgelReemisionItems,
    Auditoria,
    MedgelOutput,
    MedgelOutputItems

};
use DB;
class MedgelRemisionesController extends Controller
{
    public function ListMedgelRemision()
    {
        $data = MedgelReemision::with('items.product','client')->get();
        return response()->json($data)->setStatusCode(200);
    }
    public function GetExistenceMedgel($lote)
    {
        try {
            $data =  MedgelTechnicalReception::select(
            DB::raw("SUM(medgel_technical_reception_items.qty) as total"),
            'medgel_technical_reception.created_at as creation_sistem',
            'medgel_technical_reception.*',
            'medgel_technical_reception_items.*',
            'medgel_products.*'
            )
            ->join('medgel_technical_reception_items','medgel_technical_reception.id','medgel_technical_reception_items.id_medgel_technical_reception')
            ->join('medgel_products','medgel_technical_reception_items.id_product','medgel_products.id')
            ->where('medgel_technical_reception_items.lote',$lote)
            ->orderBy('medgel_technical_reception.created_at','ASC')
            ->groupBy('medgel_technical_reception_items.lote')
            ->with('proveedor')
            ->with('user')
            ->first();
           if($data){
               return response()->json($data)->setStatusCode(200);
           }else{
            return response()->json(["mensaje" => "No Existe el lote $lote"])->setStatusCode(400);
           }
        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function CreateMedgelRemision(Request $request)
    {
        try {
            // dd($request->all());
            // isset($request["reissue"])  ? $request["reissue"] = 1 : $request["reissue"] = 0;
            $output = new MedgelReemision;
            $output->warehouse              = $request->warehouse;
            $output->id_client              = $request->id_client;
            // $output->reissue                = $request->reissue;
            $output->subtotal               = $request->subtotal;
            $output->subtotal_with_discount = $request->subtotal_with_discount;
            $output->vat_total                = $request->vat_total;
            $output->discount_total         = $request->discount_total;
            $output->rte_fuente             = $request->rte_fuente;
            $output->rte_fuente_total       = $request->rte_fuente_total;
            $output->total_invoice          = $request->total_invoice;
            $output->save();

            $auditoria              = new Auditoria;
            $auditoria->tabla       = "medgel_remision";
            $auditoria->cod_reg     = $output->id;
            $auditoria->status      = 1;
            $auditoria->fec_regins  = date("Y-m-d H:i:s");
            $auditoria->usr_regins  = $request["id_user"];
            $auditoria->save();

                foreach ($request->id_product as $key => $value) {
                    $producs_items = [];
                    $producs_items["id_medgel_reemision"] = $output->id;
                    $producs_items["id_product"]  = $value;
                    $producs_items["lote"]         = $request["lote"][$key];
                    $producs_items["qty"]         = $request["qty"][$key];
                    $producs_items["price"]       = str_replace(",", "", $request["price"][$key]);
                    $producs_items["vat"]         = 1;
                    $producs_items["total"]       = str_replace(",", "", $request->total_invoice);

                    MedgelReemisionItems::create($producs_items);
                }
            if ($output) {
                $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente <a href='api/invoice/print/$output->id' target='_blank'>Imprimir Factura</a>");
                return response()->json($data)->setStatusCode(200);
            } else {
                return response()->json("A ocurrido un error")->setStatusCode(400);
            }
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function MedgelRemisionToInvoice($id,$user)
    {
        try {
            // dd($id,$user);
            $head = MedgelReemision::where('id',$id)->first();
            $items = MedgelReemisionItems::where('id_medgel_reemision',$head->id)->get();
         
            $output                         = new MedgelOutput;
            $output->warehouse              = $head->warehouse;
            $output->id_client              = $head->id_client;
            $output->reissue                = 0;
            $output->subtotal               = $head->subtotal;
            $output->subtotal_with_discount = $head->subtotal_with_discount;
            $output->vat_total              = $head->vat_total;
            $output->discount_total         = $head->discount_total;
            $output->rte_fuente             = $head->rte_fuente;
            $output->total_invoice          = $head->total_invoice;
            $output->observations           = $head->observations;
            $output->save();

            $auditoria              = new Auditoria;
            $auditoria->tabla       = "implantes_output";
            $auditoria->cod_reg     = $output->id;
            $auditoria->status      = 1;
            $auditoria->fec_regins  = date("Y-m-d H:i:s");
            $auditoria->usr_regins  = $user;
            $auditoria->save();

            foreach($items as $key => $value){
                $producs_items               = new MedgelOutputItems;
                $producs_items->id_medgel_output      = $output->id;
                $producs_items->referencia  = $value->referencia;
                $producs_items->lote      = $value->lote;
                $producs_items->qty         = $value->qty;
                $producs_items->price       = str_replace(",", "", $value->price);
                $producs_items->vat         = $value->vat;
                $producs_items->total       = str_replace(",", "", $value->total);
                $producs_items->save();

            }
           
            MedgelReemision::where('id',$id)->Delete();
            MedgelReemisionItems::where('id_medgel_reemision',$id)->Delete();

            $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente <a href='api/invoice/print/$output->id' target='_blank'>Imprimir Factura</a>");
            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
    }
    
}
