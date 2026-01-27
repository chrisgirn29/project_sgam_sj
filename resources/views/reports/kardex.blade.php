<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">

<style>
@page {
    size: legal landscape;
    margin: 3.5cm 2cm 2cm 2cm;
}

/* ===== REGLAS CLAVE DOMPDF ===== */
thead {
    display: table-header-group;
}

tbody {
    display: table-row-group;
}

table {
    page-break-inside: auto;
}

tr {
    page-break-inside: avoid;
}

/* ===== ESTILOS EXISTENTES ===== */
body {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 8pt;
    color: #000;
}

.header-info {
    margin-bottom: 10px;
}

.header-info table {
    width: 100%;
    border-collapse: collapse;
}

.header-info td {
    border: 1px solid #000;
    padding: 4px;
    font-size: 8pt;
}

h1 {
    text-align: center;
    font-size: 12pt;
    margin: 8px 0 12px 0;
    letter-spacing: 1px;
}

table.kardex {
    width: 100%;
    border-collapse: collapse;
}

table.kardex th,
table.kardex td {
    border: 1px solid #000;
    padding: 4px;
    vertical-align: top;
}

table.kardex th {
    font-weight: bold;
    text-align: center;
}

.left {
    text-align: left;
}

.center {
    text-align: center;
}

.small {
    font-size: 7.5pt;
}

.fecha {
    line-height: 1.2;
}

/* ===== ANCHOS CONTROLADOS ===== */
table.kardex col.proveedor { width: 8%; }
table.kardex col.fecha     { width: 21%; }

table.kardex col.ent1 { width: 6%; }
table.kardex col.ent2 { width: 5%; }
table.kardex col.ent3 { width: 7%; }
table.kardex col.ent4 { width: 8%; }

table.kardex col.sal1 { width: 6%; }
table.kardex col.sal2 { width: 5%; }
table.kardex col.sal3 { width: 8%; }

table.kardex col.salcan  { width: 6%; }
table.kardex col.salunit { width: 8%; }
table.kardex col.saltot  { width: 12%; }
</style>
</head>

<body>

<!-- ================= ENCABEZADO ================= -->
<div class="header-info">
    <table>
        <tr>
            <td>
                <strong>ARTÍCULO:</strong>
                {{ $producto->nombre }}
            </td>
            <td>
                <strong>UNIDAD DE MEDIDA:</strong>
                {{ $producto->medida }}
            </td>
            <td>
                <strong>CODIGO PRODUCTO:</strong>
                {{ $producto->id_producto }}
            </td>
        </tr>
    </table>
</div>


<h1>KARDEX DE INVENTARIO</h1>

<!-- ================= TABLA ================= -->
<table class="kardex">

<colgroup>
    <col class="proveedor">
    <col class="fecha">

    <col class="ent1">
    <col class="ent2">
    <col class="ent3">
    <col class="ent4">

    <col class="sal1">
    <col class="sal2">
    <col class="sal3">

    <col class="salcan">
    <col class="salunit">
    <col class="saltot">
</colgroup>

<thead>
<tr>
    <th rowspan="2">Proveedor / Destino</th>
    <th rowspan="2">Fecha</th>
    <th colspan="4">Entradas</th>
    <th colspan="3">Salidas</th>
    <th colspan="3">Saldos</th>
</tr>
<tr>
    <th>No. Factura</th>
    <th>Cant</th>
    <th>V. Unit</th>
    <th>Total</th>

    <th>No. Salida</th>
    <th>Cant</th>
    <th>Total</th>

    <th>Cant</th>
    <th>V. Unit</th>
    <th>Total</th>
</tr>
</thead>

<tbody>
@foreach($kardex as $k)
<tr>
    <td class="left small">{{ $k->proveedor_destino }}</td>

    <td class="center fecha small">
        {{ \Carbon\Carbon::parse($k->fecha_entrada ?? $k->fecha_evento)->format('d') }} /<br>
        {{ \Carbon\Carbon::parse($k->fecha_entrada ?? $k->fecha_evento)->translatedFormat('M') }} /<br>
        {{ \Carbon\Carbon::parse($k->fecha_entrada ?? $k->fecha_evento)->format('Y') }}
    </td>

    <td class="center small">{{ $k->numero_factura }}</td>
    <td class="center small">{{ $k->cantidad_entrada ?: '' }}</td>
    <td class="center small">
        {{ $k->valor_unitario_entrada ? 'Q '.number_format($k->valor_unitario_entrada,2) : '' }}
    </td>
    <td class="center small">
        {{ $k->valor_total_entrada ? 'Q '.number_format($k->valor_total_entrada,2) : '' }}
    </td>

    <td class="center small">{{ $k->no_salida }}</td>
    <td class="center small">{{ $k->cantidad_salida ?: '' }}</td>
    <td class="center small">
        {{ $k->valor_total_salida ? 'Q '.number_format($k->valor_total_salida,2) : '' }}
    </td>

    <td class="center small">{{ $k->saldo_cantidad }}</td>
    <td class="center small">{{ 'Q '.number_format($k->saldo_precio_unitario,2) }}</td>
    <td class="center small">{{ 'Q '.number_format($k->saldo_total,2) }}</td>
</tr>
@endforeach
</tbody>

</table>

</body>
</html>
