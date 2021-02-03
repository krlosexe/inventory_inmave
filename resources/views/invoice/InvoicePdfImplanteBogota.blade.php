<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>

    <style>
        .resolucion p {
            font-size: 12px;
            line-height: .5em;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        td {
            font-size: 12px;
        }

        #fechas td {
            padding-bottom: 2%;
        }

        #client td {
            padding-bottom: 1%;
        }

        #fechas {
            position: absolute;
            top: -10px;
            left: 400px;
        }
    </style>
</head>

<body>
    @if($warehouse == "Medellin" || $warehouse =="Cali")
    <img src="http://pdtclientsolutions.com/inventory_inmave/img/inmave.png" width="300">

    @elseif($warehouse == "Bogota" )
    <img src="http://pdtclientsolutions.com/inventory_inmave/img/silimed.jpeg" width="300">

    @else
    @endif

    <div class="resolucion">
        @if($warehouse == "Medellin" || $warehouse == "Cali")
        <h3>NIT: 900 887 221-2</h3>
        @elseif($warehouse == "Bogota" )
        <h3>NIT: 901 428 514-0</h3>
        @else
        @endif
        @if($warehouse == "Medellin" || $warehouse == "Cali")
        @if($reissue == 1)
        <p>Resolucion Autorizada por la DIAN No. 18763006154625</p>
        @endif
        @elseif($warehouse == "Bogota")
        @if($reissue == 1)
        <p>Resolucion Autorizada por la DIAN No. 18764010079751</p>
        @endif
        @else
        @endif
        @if($warehouse == "Medellin" || $warehouse == "Cali")
        @if($reissue == 1)
        <p>De Junio 02 de 2020 Numeracion 0041 a 0140</p>
        @endif
        @elseif($warehouse == "Bogota")
        @if($reissue == 1)
        <p>De Mayo 05 de 2020 Numeracion 1 a 300</p>
        @endif
        @else
        @endif
        @if($warehouse  == "Medellin" )
        @if($reissue == 1)
        @else
        <p>Facturacion por Computador</p>
        <p>No somos Autorretenedores</p>
        <p>No somos Grandes Contribuyentes</p>
        <p>Actividad Economica 4645</p>
        @endif
        @else
        @endif
    </div>
    <div id="fechas">

        @if($reissue == 0)
        @if($warehouse == "Medellin" )
        <h4>FACTURA PROFORMA {{"MED-"}}: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; {{ str_pad($id, 4, "0", STR_PAD_LEFT) }}</h4>
        @elseif($warehouse == "Bogota" )
        <h4>FACTURA PROFORMA {{"BOG-"}}: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; {{ str_pad($id, 4, "0", STR_PAD_LEFT) }}</h4>
        @else
        <h4>FACTURA PROFORMA {{"CALI-"}}: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; {{ str_pad($id, 4, "0", STR_PAD_LEFT) }}</h4>
        @endif

        @else

        @if($warehouse == "Medellin" )
        <h4>REEMISIÓN {{"MED-"}}: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; {{ str_pad($id, 4, "0", STR_PAD_LEFT) }}</h4>
        @elseif($warehouse == "Bogota" )
        <h4>REEMISIÓN {{"BOG-"}}: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; {{ str_pad($id, 4, "0", STR_PAD_LEFT) }}</h4>
        @else
        <h4>REEMISIÓN {{"CALI-"}}: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; {{ str_pad($id, 4, "0", STR_PAD_LEFT) }}</h4>
        @endif

        @endif
        <table>
            <tr>
            @if($reissue == 0)
                <td><b>Fecha de Factura:</b></td>
                @else
                <td><b>Fecha de Reemisión:</b></td>
                @endif
                </td>
                <td>{{$created_at}}</td>
            </tr>
            <tr>
                <td><b>Fecha de Vencimiento:</b></td>
                <td>{{$created_at}}</td>
            </tr>

        </table>
    </div>
    <div id="client">
        <table>
            <tr>
                <td><b>Señores:</b></td>
                </td>
                <td>{{$name_client}}</td>
                <td><b>Nit:</b></td>
                <td>{{$nit}}</td>
                <td><b>Telefono:</b></td>
                <td>{{$phone}}</td>
                <td><b>Ciudad:</b></td>
                <td>{{$city}}</td>
            </tr>DEL</td>

            </tr>
        </table>
        <br>
        <b>Dirección:</b>
        <span style="font-size:8"> {{ $address_client }}</span>
        <br><br>
        <br><br>
        <table>
            <tr>
                <td style="border-bottom: 1px solid black; text-align: center;  width: 90px;"><b>REFERENCIA</b></td>
                <td style="border-bottom: 1px solid black; text-align: center;  width: 100px;"><b>SERIAL</b></td>
                <td style="border-bottom: 1px solid black; text-align: center;  width: 90px;"><b>NOMBRE DEL ARTICULO</b></td>
                <td style="border-bottom: 1px solid black; text-align: center;  width: 100px;"><b>CANTIDAD</b></td>
                <td style="border-bottom: 1px solid black; text-align: center;  width: 110px;"><b>VLR UNITARIO</b></td>
                <td style="border-bottom: 1px solid black; text-align: center;  width: 120px;"><b>VLR TOTAL</b></td>
            </tr>
            @foreach($items as $value)
            echo $value;
            <tr>
                <td style="text-align: center">{{$value["referencia"]}}</td>
                <td style="text-align: center">{{$value["serial"]}}</td>
                <td style="text-align: center">{{$value["description"]}}</td>
                <td style="text-align: center">{{$value["qty"]}}</td>
                <td style="text-align: center">{{number_format($value["price"], 2, ',', '.')}}</td>
                <td style="text-align: center">{{number_format(($value["price"] * $value["qty"]), 2, ',', '.')}}</td>
            </tr>
            @endforeach
        </table>
        <br> <br> <br>
        <p style="font-size: 11px;">Obervaciones: {{ $observations}}</p>

        <br>
        <table width="100%" border="1" cellspacing="0" cellpadding="0">
            <tr>
                <td rowspan="5" style="border: 1px solid black; text-align: center;  width: 50px;"><b>SON:</b></td>
                <td rowspan="5" style="border: 1px solid black; text-align: center;  width: 100px;"><b style="font-size:10px"> {{$ammount_text}} </b></td>
                <td style="border: 1px solid black; text-align: center;  width: 100px;"><b>SUBTOTAL</b></td>
                <td style="border: 1px solid black; text-align: center;  width: 100px;"><b>{{number_format($subtotal, 2, ',', '.')}}</b></td>
            </tr>
            <tr>
                <td style="border: 1px solid black; text-align: center;  width: 100px;"><b>IVA</b></td>
                @if($warehouse == "Medellin" || $warehouse == "Cali")
                <td style="border: 1px solid black; text-align: center;  width: 100px;"><b>{{number_format($vat_total, 2, ',', '.')}} </b></td>
                @elseif($warehouse == "Bogota" )
                <td style="border: 1px solid black; text-align: center;  width: 100px;"><b>{{number_format(0, 2, ',', '.')}} </b></td>
                @else
                @endif
            </tr>

            <tr>
                @if($warehouse == "Medellin" || $warehouse == "Cali")
                @if($discount_type == 0)
                <td style="border: 1px solid black; text-align: center;  width: 100px;"><b>DESCUENTO (0%)</b></td>
                @endif
                @if($discount_type == 5)
                <td style="border: 1px solid black; text-align: center;  width: 100px;"><b>DESCUENTO (5%)</b></td>
                @endif
                @if($discount_type == 10)
                <td style="border: 1px solid black; text-align: center;  width: 100px;"><b>DESCUENTO (10%)</b></td>
                @endif
                @if($discount_type == 15)
                <td style="border: 1px solid black; text-align: center;  width: 100px;"><b>DESCUENTO (15%)</b></td>
                @endif

                @elseif($warehouse == "Bogota" )
                @if($discount_type == 0)
                <td style="border: 1px solid black; text-align: center;  width: 100px;"><b>DESCUENTO (0%)</b></td>
                @endif
                @if($discount_type == 5)
                <td style="border: 1px solid black; text-align: center;  width: 100px;"><b>DESCUENTO (5%)</b></td>
                @endif
                @if($discount_type == 10)
                <td style="border: 1px solid black; text-align: center;  width: 100px;"><b>DESCUENTO (10%)</b></td>
                @endif
                @if($discount_type == 15)
                <td style="border: 1px solid black; text-align: center;  width: 100px;"><b>DESCUENTO (15%)</b></td>
                @endif
                @else

                @endif
                <td style="border: 1px solid black; text-align: center;  width: 100px;"><b>{{number_format($discount_total, 2, ',', '.')}}</b></td>
            </tr>

            <tr>
                <td style="border: 1px solid black; text-align: center;  width: 100px;"><b>RTE FUENTE ({{$rte_fuente}} %)</b></td>
                <td style="border: 1px solid black; text-align: center;  width: 100px;"><b>{{number_format($rte_fuente_total, 2, ',', '.')}}</b></td>
            </tr>

            <tr>
                <td style="border: 1px solid black; text-align: center;  width: 100px;"><b>TOTAL</b></td>
                <td style="border: 1px solid black; text-align: center;  width: 100px;"><b>{{number_format($total_invoice, 2, ',', '.')}}</b></td>
            </tr>
        </table>

        <br>
        <p style="font-size: 11px;">Al efectuar su pago gire cheque a favor de INMAVE COLOMBIA SAS NIT: 900 887 221-2, Apartir del vencimiento causara el maximo interes permitido por la ley mensualmente. Esta factura se asimila en sus efectos legales a la letra de cambio art.774 C.C</p>

        <table width="100%" style="font-size: 11px">
            <tr>
                <th>Ventas: </th>
                <th>Recibio: </th>
            </tr>
            <tr>
                <th> </th>
                <th></th>
            </tr>
            <tr>
                <th> </th>
                <th></th>
            </tr>
            <tr>
                <th style="padding-top: 20px;"> </th>
                <th style="padding-top: 20px;"></th>
            </tr>

            <tr>
                @if($warehouse == "Medellin" || $warehouse == "Cali")
                <th style="border-top: 1px solid black;">INMAVE COLOMBIA SAS</th>
                @elseif($warehouse == "Bogota" )
                <th style="border-top: 1px solid black;">S&S MEDICAL DISPOSITVES SAS</th>
                @else
                @endif
                <th style="border-top: 1px solid black;"> Firma y sello de Recibido</th>
            </tr>
        </table>
        <br><br>
        @if($warehouse == "Medellin" || $warehouse == "Cali")
        <p style="font-size: 11px"><b>INMAVE COLOMBIA SAS - NIT 900 887 221-2, CUENTA CORRIENTE BANCOLOMBIA N° 63451049234</b></p>
        <p style="font-size: 11px">Cra 43A #17-106 of 902 Telefono: 3220471 Correo: info@inmavecolombia.com www.inmavecolombia.com</p>
        @elseif($warehouse == "Bogota" )
        <p style="font-size: 11px"><b>SILIMED COLOMBIA SAS - NIT 901130935, CUENTA CORRIENTE BANCOLOMBIA N° 67400012942</b></p>
        <p style="font-size: 11px">Se deben efectuar en Bancolombia "convenio de recaudo 87622", se debe indicar en la referencia el número de factura a pagar</p>
        @else
        @endif

    </div>

</body>

</html>