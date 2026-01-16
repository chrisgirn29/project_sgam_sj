<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Autorización</title>

<style>
@page {
    size: Letter;
    margin: 1.5cm;
}

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: "Times New Roman", Times, serif;
    color: #000;
    background: #fff;
}

.page {
    width: 100%;
}

/* ===== HEADER ===== */
.header-box {

    padding: 8px;
    width: 100%;
    margin-bottom: 6px;
}

.header-table {
    width: 100%;
    border-collapse: collapse;
}

.logo-box {
    width: 110px;

    text-align: center;
    padding: 6px;
}

.logo-box img {
    width: 75px;
}

.title-box {
    padding-left: 40px;
    padding: 8px 14px;
    text-align: center;
    vertical-align: middle;
}

.title-box .small {
     padding-left: 40px;
    font-size: 11px;
    letter-spacing: 1px;
    font-weight: 600;
}

.title-box h1 {
    margin: 4px 0 2px;
    font-size: 21px;
    letter-spacing: 1.5px;
}

.title-box h2 {
    margin: 0;
    font-size: 13.5px;
    font-weight: 500;
}

/* ===== FECHA ===== */
.date-box {
    border: 1.1px solid #6fae2d;
    padding: 6px 12px;
    font-size: 13.5px;
    margin-bottom: 6px;
}

/* ===== TEXTO ===== */
.text-box {
    border: 1.1px solid #6fae2d;
    padding: 12px 16px;
    font-size: 13.5px;
    line-height: 1.5;
    text-align: justify;
    margin-bottom: 8px;
    background: linear-gradient(to right, #8ebb60, #ffffff);
}

/* ===== TABLA ===== */
.table-wrapper {
    border: 1.5px solid #6fae2d;
}

.table-pdf {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

.table-pdf thead th {
    background: #4F870F;
    color: #fff;
    font-size: 12.5px;
    padding: 8px;
    text-transform: uppercase;
    letter-spacing: 1px;
    border-right: 1px solid #fff;
}

.table-pdf thead th:last-child {
    border-right: none;
}

.table-pdf tbody td {
    font-size: 13.5px;
    padding: 8px;
    border-top: 1px solid #1D520D;
    border-right: 1px solid #1D520D;
    vertical-align: top;
}

.table-pdf tbody td:last-child {
    border-right: none;
}

.col-qty {
    width: 18%;
    text-align: center;
    font-weight: 700;
}

/* ===== OBSERVACIONES ===== */
.observations {
    border-top: 2.5px solid #4F870F;
    background: #f8f8f8;
    padding: 8px 14px;
    font-size: 13px;
    line-height: 1.45;
}

/* ===== FIRMA ===== */
.signature {
    margin-top: 45px;
    text-align: center;
}

.signature .employee {
    font-size: 13.5px;
    font-weight: 700;
    margin-bottom: 28px;
}

.signature span {
    font-size: 13.5px;
    font-weight: 700;
    letter-spacing: 1px;
}
</style>
</head>

<body>
<div class="page">

<!-- HEADER -->
<div class="header-box">
    <table class="header-table">
        <tr>
            <td class="logo-box">
                <img src="{{ public_path('logo1.jpg') }}" alt="Encabezado">
            </td>
            <td class="title-box">
                <h1>AUTORIZACIÓN</h1>
                <div class="small">Almacén: General</div>
                <h2>Equipo, Mobiliario, Suministros de Oficina y Otros</h2>
            </td>
        </tr>
    </table>
</div>

<!-- FECHA -->
<div class="date-box" style="text-align:right;">
    San Jerónimo, Baja Verapaz —
    <strong>{{ $fechaLarga }}</strong>
</div>

<!-- TEXTO -->
<div class="text-box">
    Con base a la solicitud de fecha
    <strong>
        {{ \Carbon\Carbon::parse($requisicion->fecha)->translatedFormat('d \d\e F \d\e Y') }}
    </strong>
    girada a este despacho por el/la interesado(a):
    <strong>{{ $requisicion->empleado->nombre_completo }}</strong>,
    se otorga la presente <strong>AUTORIZACIÓN</strong> para la gestión de adquisición
    de los <strong>BIENES, INSUMOS Y SUMINISTROS</strong> que se describen a continuación:
</div>

<!-- TABLA -->
<div class="table-wrapper">
    <table class="table-pdf">
        <thead>
            <tr>
                <th class="col-qty">Cantidad</th>
                <th>Descripción de los insumos y/o servicios</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requisicion->detalles as $detalle)
            <tr>
                <td class="col-qty">
                    {{ number_format($detalle->cantidad, 2) }}
                </td>
                <td>
                    {{ $detalle->producto->nombre }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="observations">
        <strong>Observaciones:</strong><br>
        {{ $requisicion->descripcion }}
    </div>
</div>

<!-- FIRMA -->
<div class="signature">
    <div class="employee">
        {{ $requisicion->empleado->nombre_completo }}<br>
        <span>{{ $requisicion->empleado->puesto }}</span>
    </div>
</div>

</div>
</body>
</html>
