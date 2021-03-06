<?php

namespace App\Exports;

use App\ProductusOutput;

use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class ClientsExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    
  
    var $date_init;
    var $date_finish;

    public function view(): View
    {
        $date_init     = $this->date_init;
        $date_finish   = $this->date_finish;

        $data = ProductusOutput::select("product_output.*", "clients.name as name_client","auditoria.*", "user_registro.email as email_regis")
                                ->join("auditoria", "auditoria.cod_reg", "=", "product_output.id")
                                ->join("clients", "clients.id", "=", "product_output.id_client")
                                ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
                                ->where("auditoria.tabla", "products_output")
                                ->where("auditoria.status", "!=", "0")
                                ->where(function ($query) use ($date_init) {
                                    if($date_init != 0){
                                        $query->where("auditoria.fec_regins", ">=", $date_init);
                                    }
                                })

                                ->where(function ($query) use ($date_finish) {
                                    if($date_finish != 0){
                                        $query->where("auditoria.fec_regins", "<=", $date_finish);
                                    }
                                })
                                ->orderBy("product_output.id", "DESC")
                                ->with("products")
                                ->get();

        


        return view('exports.clients', [
            'data' => $data
        ]);
    }



    public function headings(): array
    {
        return [
           
        ];
    }



    public function collection()
    {
        
    }
}
