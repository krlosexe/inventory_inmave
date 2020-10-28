<?php

namespace App\Exports;

use App\ProductusOutput;
use App\Reemisiones;

use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class ClientsExportReemision implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */




    public function view(): View
    {

        $data = Reemisiones::select("reemisiones.*", "clients.name as name_client","auditoria.*", "user_registro.email as email_regis")
                                ->join("auditoria", "auditoria.cod_reg", "=", "reemisiones.id")
                                ->join("clients", "clients.id", "=", "reemisiones.id_client")
                                ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
                                ->where("auditoria.tabla", "products_output")
                                ->where("auditoria.status", "!=", "0")
                                ->orderBy("reemisiones.id", "DESC")
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
