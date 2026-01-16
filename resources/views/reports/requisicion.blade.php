<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">

<style>
@page {
    size: A4;
    margin: 25px;
}

* {
    page-break-inside: avoid !important;
}

body {
    margin: 0;
    padding: 0;
    font-family: "Times New Roman", serif;
    font-size: 10.5px;
}

.box {
    border: 1px solid #000;
    border-radius: 6px;
    padding: 4px;
    text-align: center;
}

.row {
    width: 100%;
    margin-bottom: 4px;
    clear: both;
}

.col {
    float: left;
    margin-right: 4px;
    box-sizing: border-box;
}

.w-10 { width: 10%; }
.w-25 { width: 25%; }
.w-60 { width: 60%; }

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 4px;
}

th, td {
    border: 1px solid #000;
    padding: 4px;
    text-align: center;
    font-size: 10px;
    vertical-align: middle;
}

.celda-alta {
    height: 790px;
    display: block;
}

.descripcion {
    text-align: left;
}

.observaciones {
    border: 1px solid #000;
    padding: 6px;
    height: 55px;
    margin-top: 4px;
    font-size: 10px;
}

.firma {
    height: 55px;
    border: 1px solid #000;
    border-radius: 6px;
    font-size: 9px;
    text-align: center;
    position: relative;   /* contenedor */
}

.texto-firma {
    position: absolute;
    bottom: 4px;          /* ⬅️ texto abajo */
    left: 0;
    width: 100%;
}



.clearfix {
    clear: both;
}
</style>
</head>

<body>

@php
    use Carbon\Carbon;
    $fecha = Carbon::parse($requisicion->fecha);
@endphp

<!-- FECHA Y PROGRAMA -->
<div class="row">
    <div class="col w-10 box">DÍA</div>
    <div class="col w-10 box">MES</div>
    <div class="col w-10 box">AÑO</div>
    <div class="col w-60 box">DEPENDENCIA O UNIDAD ADMINISTRATIVA</div>
</div>
<div class="row">
    <div class="col w-10 box">{{ $fecha->format('d') }}</div>
    <div class="col w-10 box">{{ $fecha->format('m') }}</div>
    <div class="col w-10 box">{{ $fecha->format('Y') }}</div>
    <div class="col w-60 box">{{ $requisicion->programa_nombre }}</div>
</div>

<div class="clearfix"></div>

<table>
<thead>
<tr>
    <th>PROGRAMA</th>
    <th>RENGLÓN</th>
    <th>UNIDADES</th>
    <th>DESCRIPCIÓN DE LOS BIENES, MATERIALES O SERVICIOS</th>
    <th>UNIDAD</th>
    <th>VALOR</th>
    <th>TOTAL</th>
</tr>
</thead>

<tbody>
<tr>

    <!-- PROGRAMA -->
    <td>
        <div class="celda-alta">

        </div>
    </td>

    <!-- RENGLÓN -->
    <td>
        <div class="celda-alta">

        </div>
    </td>

    <!-- UNIDADES -->
    <td>
        <div class="celda-alta">
            @foreach($detalles as $d)
                {{ $d->cantidad }}<br>
            @endforeach
        </div>
    </td>

    <!-- DESCRIPCIÓN -->
    <td class="descripcion">
        <div class="celda-alta">
            @foreach($detalles as $d)
                {{ $d->producto_nombre }}<br>
            @endforeach
        </div>
    </td>

    <!-- UNIDAD -->
    <td>
        <div class="celda-alta">UNIDAD</div>
    </td>

    <!-- VALOR -->
    <td>
        <div class="celda-alta"></div>
    </td>

    <!-- TOTAL -->
    <td>
        <div class="celda-alta"></div>
    </td>

</tr>
</tbody>

</table>

<!-- OBSERVACIONES -->
<div class="observaciones">
    <strong>Observaciones:</strong><br>
    {{ $requisicion->descripcion }}
</div>

<!-- FIRMAS -->
<div class="row" style="margin-top:6px;">
  <div class="col w-25 firma">
    <span class="texto-firma">
        {{ $requisicion->nombre_completo }}<br>
        Firma del solicitante
    </span>
</div>


    <div class="col w-25 firma">
        <span class="texto-firma">
        FIRMA JEFE DE LA OFICINA
        </span>
    </div>

    <div class="col w-25 firma">
        <span class="texto-firma">
        FIRMA GUARDA ALMACÉN
        </span>
    </div>

    <div class="col w-25 firma">
        <span class="texto-firma">
         FIRMA AUTORIZA
        </span>
    </div>
</div>

</body>
</html>
