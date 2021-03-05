<table>
    <thead>
        <tr>
            <th><b>Factura</b></th>
            <th><b>Tipo</b></th>
            <th><b>Cliente</b></th>
            <th><b>Bodega</b></th>
            <th><b>Productos</b></th>
            <th><b>Subtotal</b></th>
            <th><b>IVA</b></th>
            <th><b>Valor de Factura</b></th>
            <th><b>Fecha de registro</b></th>
            <th><b>Registrado Por</b></th>
        </tr>
    </thead>
    <tbody>
    @foreach($data as $value)
        <tr>
            <td>{{ $value->id }}</td>
            <td>{{ $value->reissue == 1 ? 'Reemision' : 'Factura' }}</td>
            <td>{{ $value->name_client }}</td>
            <td>{{ $value->warehouse }}</td>
            <td>
                
                @foreach($value->products as $value2)
                  {{$value2->description}} cantidad : {{$value2->qty}}, <br>
                @endforeach
            </td>
            <td>{{ $value->Subtotal }}</td>
            <td>{{ $value->vat_total }}</td>
            <td>{{ $value->total_invoice }}</td>
            <td>{{ $value->fec_regins }}</td>
            <td>{{ $value->email_regis }}</td>
        </tr>
    @endforeach
    </tbody>
</table>