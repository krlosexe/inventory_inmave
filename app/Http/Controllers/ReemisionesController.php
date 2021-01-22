<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\{
    Auditoria,
    ReemisionesItems,
    Reemisiones,
    ProductusOutput,
    ProductusOutputItems,
    ImplanteReemision,
    ImplanteReemisionesItem,
    ImplantOutput,
    ImplantOutputItems,
    TechnicalReceptionImplante,
    TechnicalReceptionProductoImplante
};

class ReemisionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Reemisiones::select("reemisiones.*", "clients.name as name_client","auditoria.*", "user_registro.email as email_regis")
                                ->join("auditoria", "auditoria.cod_reg", "=", "reemisiones.id")
                                ->join("clients", "clients.id", "=", "reemisiones.id_client")
                                ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
                                ->where("auditoria.tabla", "reemisiones")
                                ->where("auditoria.status", "!=", "0")
                                ->orderBy("reemisiones.id", "DESC")
                                ->with("products")
                                ->get();

        $total = 0;
        foreach($data as $value){

            foreach($value["products"] as $product){

                $entry_medellin = DB::table("product_entry_items")
                            ->selectRaw("product_entry_items.id_product, products.description, (SUM(product_entry_items.qty))  as total")
                            ->join("products_entry", "products_entry.id", "product_entry_items.id_entry")
                            ->join("products", "products.id", "product_entry_items.id_product")
                            ->where("products_entry.warehouse", "Medellin")
                            ->where("products.id", $product["id_product"])
                            ->groupBy("product_entry_items.id_product")
                            ->first();

                $output_medellin = DB::table("product_output_items")
                                    ->selectRaw("product_output_items.id_product, products.description, (SUM(product_output_items.qty))  as total")
                                    ->join("product_output", "product_output.id", "product_output_items.id_output")
                                    ->join("products", "products.id", "product_output_items.id_product")
                                    ->where("product_output.warehouse", "Medellin")
                                    ->where("products.id", $product["id_product"])
                                    ->groupBy("product_output_items.id_product")
                                    ->first();


                $total = 0;
                if($entry_medellin){
                    $total_output_medellin = 0;
                    if($output_medellin){
                        $total_output_medellin = $output_medellin->total;
                    }

                    $total = $entry_medellin->total - $total_output_medellin;
                }else{
                    $total = 0;
                }
            }
            $product["existence"] = $total;

        }
        return response()->json($data)->setStatusCode(200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        isset($request["reissue"])  ? $request["reissue"] = 1 : $request["reissue"] = 0;
        $output = Reemisiones::create($request->all());
        $auditoria              = new Auditoria;
        $auditoria->tabla       = "reemisiones";
        $auditoria->cod_reg     = $output->id;
        $auditoria->status      = 1;
        $auditoria->fec_regins  = date("Y-m-d H:i:s");
        $auditoria->usr_regins  = $request["id_user"];
        $auditoria->save();


        if(isset($request->id_product)){
            foreach($request->id_product as $key => $value){
                $producs_items = [];
                $producs_items["id_reemision"]   = $output->id;
                $producs_items["id_product"]  = $value;
                $producs_items["qty"]         = $request["qty"][$key];
                $producs_items["price"]       = str_replace(",", "", $request["price"][$key]);
                $producs_items["vat"]         = $request["vat"][$key];
                $producs_items["total"]       = str_replace(",", "", $request["total"][$key]);

                ReemisionesItems::create($producs_items);
            }
        }


        if ($output) {
            $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente <a href='api/invoice/print/$output->id' target='_blank'>Imprimir Factura</a>");
            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json("A ocurrido un error")->setStatusCode(400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Reemisiones  $reemisiones
     * @return \Illuminate\Http\Response
     */
    public function show(Reemisiones $reemisiones)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Reemisiones  $reemisiones
     * @return \Illuminate\Http\Response
     */
    public function edit(Reemisiones $reemisiones)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Reemisiones  $reemisiones
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $reemisiones)
    {
        $update = Reemisiones::find($reemisiones)->update($request->all());
        ReemisionesItems::where("id_reemision", $reemisiones)->delete();
        if(isset($request["id_product"])){
            foreach($request["id_product"] as $key => $value){
                $producs_items["id_reemision"]  = $reemisiones;
                $producs_items["id_product"]     = $value;
                $producs_items["qty"]           = $request["qty"][$key];
                $producs_items["price"]         = str_replace(",", "", $request["price"][$key]);
                $producs_items["vat"]           = $request["vat"][$key];
                $producs_items["total"]         = str_replace(",", "", $request["total"][$key]);
                ReemisionesItems::create($producs_items);
            }
        }

        if ($update) {
            $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json("A ocurrido un error")->setStatusCode(400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reemisiones  $reemisiones
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reemisiones $reemisiones)
    {
        //
    }

    public function RemisionToInvoice($id,$user)
    {
        try {
            $head = Reemisiones::where('id',$id)->first();
            $items = ReemisionesItems::where('id_reemision',$id)->get();
            // dd($head->warehouse);
            $output                         = new ProductusOutput;
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
            $auditoria->tabla       = "products_output";
            $auditoria->cod_reg     = $output->id;
            $auditoria->status      = 1;
            $auditoria->fec_regins  = date("Y-m-d H:i:s");
            $auditoria->usr_regins  = $user;
            $auditoria->save();

            foreach($items as $key => $value){

                $producs_items               = new ProductusOutputItems;
                $producs_items->id_output   = $output->id;
                $producs_items->id_product  = $value->id_product;
                $producs_items->qty         = $value->qty;
                $producs_items->price       = str_replace(",", "", $value->price);
                $producs_items->vat         = $value->vat;
                $producs_items->total       = str_replace(",", "", $value->total);
                $producs_items->save();

            }

             Reemisiones::where('id',$id)->Delete();
             ReemisionesItems::where('id_reemision',$id)->Delete();

            $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente <a href='api/invoice/print/$output->id' target='_blank'>Imprimir Factura</a>");
            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function ImplantesRemisionToInvoice($id,$user)
    {
        try {
            $head = ImplanteReemision::where('id',$id)->first();
            // dd($head);
            $items = ImplanteReemisionesItem::where('id_implante_reemision',$head->id)->get();
            // dd($items);
            // dd($head->warehouse);
            $output                         = new ImplantOutput;
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
            $output->estatus                = "Vendido";
            $output->save();

            $auditoria              = new Auditoria;
            $auditoria->tabla       = "implantes_output";
            $auditoria->cod_reg     = $output->id;
            $auditoria->status      = 1;
            $auditoria->fec_regins  = date("Y-m-d H:i:s");
            $auditoria->usr_regins  = $user;
            $auditoria->save();

            foreach($items as $key => $value){
                $producs_items               = new ImplantOutputItems;
                $producs_items->id_implant_output      = $output->id;
                $producs_items->referencia  = $value->referencia;
                $producs_items->serial      = $value->serial;
                $producs_items->qty         = $value->qty;
                $producs_items->price       = str_replace(",", "", $value->price);
                $producs_items->vat         = $value->vat;
                $producs_items->total       = str_replace(",", "", $value->total);
                // $producs_items->estatus     = "Vendido";
                $producs_items->save();

                $detail = TechnicalReceptionProductoImplante::where('serial',$value->serial)->first();
                    TechnicalReceptionImplante::where("id",$detail->id_technical_reception_implante)->update(["estatus" => "Vendido"]);

            }
            ImplanteReemision::where('id',$id)->update(["estatus" => "Vendido"]);
            // ImplanteReemisionesItem::where('id_implante_reemision',$id)->update(["estatus" => "Vendido"]);
            
            // ImplanteReemision::where('id',$id)->Delete();
            // ImplanteReemisionesItem::where('id_implante_reemision',$id)->Delete();

            $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente <a href='api/invoice/print/$output->id' target='_blank'>Imprimir Factura</a>");
            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
