<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Constancia de no Existencia</title>

<style>
@page {
    size: Letter;
    margin: 1.5cm;
}

body {
    margin: 0;
    color: #000000;
    font-family: "Times New Roman", serif;
    font-size: 12px; /* 🔥 TODO EL DOCUMENTO A 10 */
}

/* ===== HEADER ===== */
.header-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 8px;
}

.logo-box {
    width: 100px;
    text-align: center;
}

.logo-box img {
    width: 75px;
}

.title-box {
    text-align: center;
}

.title-box h1 {
    margin: 0;
    font-size: 18px; /* 🔥 TÍTULO PRINCIPAL MÁS GRANDE */
    letter-spacing: 2px;
}

.title-box h2 {
    margin: 0;
    font-size: 10px;
    font-weight: normal;
}

.small {
    font-size: 10px;
    font-weight: bold;
    color: #4F870F;
}

/* ===== FECHA ===== */
.date-box {
    border-left: 4px solid #6fae2d;
    background: #f4f6f4;
    padding: 6px 10px;
    margin-bottom: 8px;
    text-align: right;
}

/* ===== TEXTO ===== */
.text-box {
    border: 1px solid #6fae2d;
    padding: 10px;
    margin-bottom: 12px;
    line-height: 1.5;
}

/* ===== TABLA ===== */
.table-pdf {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid #6fae2d;
}

/* CLAVE DOMPDF */
.table-pdf thead {
    display: table-header-group;
}

.table-pdf thead th {
    background: #6fae2d;
    color: #000000 !important;
    font-size: 11.5px;
    padding: 8px;
    text-align: center;
    border: 1px solid #6fae2d;
}

.table-pdf tbody td {
    font-size: 10px;
    padding: 8px;
    border: 1px solid #dcdcdc;
    color: #000000;
}

.table-pdf tbody tr:nth-child(even) {
    background: #f4f6f4;
}

/* ===== OBSERVACIONES ===== */
.observations {
    border: 1px solid #4F870F;
    border-top: none;
    padding: 10px;
    margin-bottom: 4px;
    line-height: 1.5;
}

/* ===== TEXTO POST OBSERVACIONES ===== */
.text-post {
    padding: 0 10px;
    margin-bottom: 30px;
    line-height: 1.5;
}

/* ===== FIRMA ===== */
.signature {
    text-align: center;
    margin-top: 110px;
}

.signature strong {
    display: block;
    margin-bottom: 4px;
    font-size: 10px;
}
</style>
</head>

<body>

<!-- HEADER -->
<table class="header-table">
    <tr>
        <td class="logo-box">
            <img src="{{ public_path('logo1.jpg') }}">
        </td>
        <td class="title-box">
            <h1>CONSTANCIA DE NO EXISTENCIA</h1>
            <div class="small">Almacén: General</div>
            <h2>BIENES, INSUMOS Y SUMINISTROS DE ALMACEN</h2>
        </td>
    </tr>
</table>

<!-- FECHA -->
<div class="date-box">
    San Jerónimo, Baja Verapaz — <strong>{{ $fechaLarga }}</strong>
</div>

<!-- TEXTO -->
<div class="text-box">
    Con base a la solicitud de fecha
    <strong>{{ \Carbon\Carbon::parse($requisicion->fecha)->translatedFormat('d \d\e F \d\e Y') }}</strong>
    girada por:
    <strong>{{ $requisicion->empleado->nombre_completo }}</strong>,
    se hace constar que los bienes, insumos y suministros que se describen a continuación:
</div>

<!-- TABLA -->
<table class="table-pdf" border="1">
    <thead>
        <tr>
            <th width="25%">CANTIDAD</th>
            <th width="75%">NOMBRE DEL PRODUCTO</th>
        </tr>
    </thead>
    <tbody>
        @foreach($requisicion->detalles as $detalle)
        <tr>
            <td align="center">
                <strong>{{ number_format($detalle->cantidad, 2) }}</strong>
            </td>
            <td>{{ $detalle->producto->nombre }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- OBSERVACIONES -->
<div class="observations">
    <strong>Observaciones:</strong><br>
    {{ $requisicion->descripcion }}
</div>

<!-- TEXTO FUERA PERO PEGADO -->
<div class="text-post">
    No existe la disponibilidad física dentro de esta unidad, para poder ser entregados;
    por lo que se realizará la gestión necesaria para poder brindar los insumos a la
    brevedad posible, respetando los procedimientos establecidos para dichas adquisiciones.
</div>

<!-- FIRMA -->
<div class="signature">
    <strong>{{ $requisicion->empleado->nombre_completo }}</strong>
    <strong>{{ $requisicion->empleado->puesto }}</strong>
</div>

</body>
</html>
