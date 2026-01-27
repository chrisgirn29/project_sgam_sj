<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Constancia de Disponibilidad Presupuestaria</title>

<style>
@page{
    size: Letter;
    margin: 2cm;
}

*{
    box-sizing:border-box;
    font-family:"Times New Roman", serif;
    font-size:11px;
}

body{
    margin:0;
    padding:0;
    color:#000;
}

/* ===== CONTENEDOR GENERAL ===== */
.documento{
    border:3px solid #000;
    padding:12px;
}

/* ===== ENCABEZADO ===== */
.header{
    background:#000;
    color:#fff;
    text-align:center;
    padding:10px 0;
    font-weight:bold;
    font-size:16px;
    letter-spacing:.5px;
    margin-bottom:10px;
}

/* ===== BLOQUE SUPERIOR ===== */
.info-top{
    width:100%;
    border-collapse:collapse;
    margin-bottom:10px;
}

.info-top td{
    padding:4px 6px;
    vertical-align:top;
}

.info-top .label{
    font-weight:bold;
}

.linea{
    border-top:2px solid #000;
    margin:10px 0;
}

/* ===== ENTIDAD ===== */
.entidad{
    text-align:center;
    font-weight:bold;
    margin-bottom:8px;
}

/* ===== TABLA ENTIDAD ===== */
.tabla-entidad{
    width:100%;
    border-collapse:collapse;
    margin-bottom:10px;
}

.tabla-entidad td{
    padding:5px;
    border-bottom:1px solid #000;
}

.tabla-entidad .label{
    width:30%;
    font-weight:bold;
}

/* ===== UNIDAD VERDE ===== */
.unidad{
    background:#6fae2d;
    color:#000;
    font-weight:bold;
    text-align:center;
    padding:6px;
}

/* ===== DESCRIPCIÓN ===== */
.descripcion{
    padding:6px 4px;
    line-height:1.5;
}

/* ===== DISPONIBILIDAD ===== */
.disponibilidad{
    font-weight:bold;
    margin:10px 0;
}

/* ===== TABLA PRESUPUESTARIA ===== */
.tabla{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
}

.tabla th{
    background:#6fae2d;
    border:1px solid #000;
    padding:4px;
    font-weight:bold;
    text-align:center;
}

.tabla td{
    border:1px solid #000;
    padding:4px;
}

/* ===== TOTAL ===== */
.total{
    margin-top:12px;
    width:100%;
}

.total td{
    padding-top:6px;
    font-weight:bold;
}

/* ===== FIRMA ===== */
.firma{
    margin-top:80px; /* MÁS ESPACIO */
    text-align:center;
}

.firma .nombre{
    margin-top:35px;
    font-weight:bold;
}

.firma .cargo{
    font-size:10px;
}
</style>
</head>

<body>

@php
function numeroALetras($numero)
{
    $numero = intval($numero);

    $unidades = ['', 'UNO','DOS','TRES','CUATRO','CINCO','SEIS','SIETE','OCHO','NUEVE'];
    $especiales = ['DIEZ','ONCE','DOCE','TRECE','CATORCE','QUINCE','DIECISÉIS','DIECISIETE','DIECIOCHO','DIECINUEVE'];
    $decenas = ['', '', 'VEINTE','TREINTA','CUARENTA','CINCUENTA','SESENTA','SETENTA','OCHENTA','NOVENTA'];
    $centenas = ['', 'CIENTO','DOSCIENTOS','TRESCIENTOS','CUATROCIENTOS','QUINIENTOS','SEISCIENTOS','SETECIENTOS','OCHOCIENTOS','NOVECIENTOS'];

    if ($numero == 0) return 'CERO';
    if ($numero == 100) return 'CIEN';

    $texto = '';
    if ($numero >= 100) {
        $texto .= $centenas[intval($numero / 100)] . ' ';
        $numero %= 100;
    }
    if ($numero >= 10 && $numero < 20) {
        return trim($texto . $especiales[$numero - 10]);
    }
    if ($numero >= 20) {
        $texto .= $decenas[intval($numero / 10)];
        if ($numero % 10 != 0) {
            $texto .= ' Y ' . $unidades[$numero % 10];
        }
    } else {
        $texto .= $unidades[$numero];
    }
    return trim($texto);
}
@endphp

<div class="documento">

    <div class="header">
        Constancia de Disponibilidad Presupuestaria
    </div>

    <table class="info-top">
        <tr>
            <td width="60%">
                <span class="label">MONTO RESERVADO VIGENTE Q.</span> {{ number_format($cdp->monto,2) }}<br>
                <span class="label">MONTO EN LETRAS:</span>
                ({{ numeroALetras($cdp->monto) }} QUETZALES 00/100)
            </td>
            <td width="40%">
                <span class="label">CDP No.</span> {{ $cdp->id_cdp }}<br>
                <span class="label">Ejercicio Fiscal:</span> {{ \Carbon\Carbon::parse($cdp->fecha)->year }}
            </td>
        </tr>
    </table>

    <div class="linea"></div>

    <div class="entidad">
        MUNICIPALIDAD DE SAN JERÓNIMO BAJA VERAPAZ
    </div>

    <table class="tabla-entidad">
        <tr>
            <td class="label">ENTIDAD:</td>
            <td></td>
        </tr>
        <tr>
            <td class="label">UNIDAD:</td>
            <td class="unidad">ALCALDÍA MUNICIPAL</td>
        </tr>
        <tr>
            <td class="label">RESPONSABLE:</td>
            <td>{{ $cdp->empleado }}</td>
        </tr>
        <tr>
            <td class="label">FECHA DE EMISIÓN:</td>
            <td>{{ \Carbon\Carbon::parse($cdp->fecha)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="label">MODALIDAD DE COMPRA O CONTRATACIÓN:</td>
            <td>NO APLICA</td>
        </tr>
    </table>

    <div class="descripcion">
        <strong>DESCRIPCIÓN DEL PROCESO:</strong><br>
        {{ $cdp->descripcion }}
    </div>

    <div class="disponibilidad">
        DISPONIBILIDAD: {{ strtoupper($cdp->tipo_disponibilidad ?? 'CON DISPONIBILIDAD') }}
    </div>

    <table class="tabla">
        <thead>
            <tr>
                <th>Programa</th>
                <th>Sub</th>
                <th>Proyecto</th>
                <th>Actividad</th>
                <th>Obra</th>
                <th>Renglón</th>
                <th>Fuente</th>
                <th>Monto</th>
            </tr>
        </thead>
        <tbody>
        @foreach($detalle as $d)
            <tr>
                <td>{{ $d->programa }}</td>
                <td>{{ $d->subprograma }}</td>
                <td>{{ $d->proyecto }}</td>
                <td>{{ $d->actividad }}</td>
                <td>{{ $d->obra }}</td>
                <td>{{ $d->renglon }}</td>
                <td>{{ $d->fuente }}</td>
                <td align="right">Q {{ number_format($d->monto,2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <table class="total">
        <tr>
            <td width="80%" align="right">Total</td>
            <td width="20%">Q {{ number_format($cdp->monto,2) }}</td>
        </tr>
    </table>

    <div class="firma">
        _______________________________<br>
        <div class="nombre">Christopher Eduardo José Ruiz Girón</div>
        <div class="cargo">Encargado de Presupuesto</div>
    </div>

</div>

</body>
</html>
