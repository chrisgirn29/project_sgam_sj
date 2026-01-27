<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Recepción de Bienes</title>

<style>
@page {
    size: Letter;
    margin: 5cm 1.5cm 2cm 1.5cm;
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

/* ===== ESTILOS ===== */
.box { border: 1px solid #000; border-radius: 6px; padding: 4px; text-align: center; }
.row { width: 100%; margin-bottom: 4px; clear: both; }
.col { float: left; margin-right: 4px; box-sizing: border-box; }

.w-6 { width: 6%; } .w-8 { width: 8%; } .w-20 { width: 20%; }
.w-25 { width: 25%; } .w-30 { width: 30%; } .w-40 { width: 40%; }
.w-50 { width: 50%; } .w-70 { width: 70%; } .w-80 { width: 80%; }
.w-firma { width: 24%; }

.clearfix { clear: both; }

table { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
td, th { border: 1px solid #000; padding: 3px 4px; font-size: 10px; }

.label { font-weight: bold; font-size: 8.5px; }
.center { text-align: center; }
.right { text-align: right; }
.left { text-align: left; }
.no-border td { border: none; }

.firma-container {
    height: 75px;
    border: 1px solid #000;
    border-radius: 6px;
    font-size: 9px;
    text-align: center;
    position: relative;
    page-break-inside: avoid;
}

.texto-firma { position: absolute; bottom: 4px; width: 100%; }

.observaciones-box {
    height: 75px;
    border: 1px solid #000;
    padding: 6px;
    font-size: 10px;
}

.total-row { font-weight: bold; }
.separator { margin: 10px 0; }
.descripcion { text-align: left; }

.page { page-break-after: always; }
</style>
</head>

<body>

@php
use Carbon\Carbon;

$fecha = Carbon::parse($header->fecha_recepcion);
$totalGlobal = $detalle->sum('subtotal');
$filasPorPagina = 21;
$paginas = $detalle->chunk($filasPorPagina);

// Función para convertir número a letras (igual que antes)
function numeroALetras($numero) {
    $partes = explode('.', number_format($numero, 2, '.', ''));
    $entero = (int)$partes[0];
    $decimales = isset($partes[1]) ? $partes[1] : '00';

    $unidades = ['', 'UNO', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'];
    $decenas = ['', 'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
    $especiales = ['DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISEIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE'];
    $centenas = ['', 'CIEN', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'];

    if ($entero == 0) $texto = 'CERO';
    else {
        $texto = '';
        if ($entero >= 1000) {
            $miles = floor($entero / 1000);
            $texto .= ($miles == 1 ? 'MIL ' : convertirCientos($miles, $unidades, $decenas, $especiales, $centenas).' MIL ');
            $entero %= 1000;
        }
        if ($entero > 0) $texto .= convertirCientos($entero, $unidades, $decenas, $especiales, $centenas);
    }

    $texto = trim($texto);
    $resultado = $texto . ' QUETZALES';
    $resultado .= ($decimales != '00') ? ' CON '.numeroALetrasCorto($decimales).' CENTAVOS' : ' EXACTOS';
    return $resultado;
}

function convertirCientos($numero, $unidades, $decenas, $especiales, $centenas) {
    $texto = '';
    $centena = floor($numero/100);
    if($centena>0) { $texto .= ($centena==1 && $numero%100==0 ? 'CIEN ' : $centenas[$centena].' '); $numero%=100; }
    if($numero>0){
        if($numero>=10 && $numero<=19) $texto .= $especiales[$numero-10].' ';
        else {
            $decena=floor($numero/10); $unidad=$numero%10;
            if($decena>0){
                if($decena==2 && $unidad>0) $texto.='VEINTI'.strtolower($unidades[$unidad]).' ';
                else { $texto.=$decenas[$decena]; if($unidad>0) $texto.=' Y '; else $texto.=' '; }
            }
            if($unidad>0 && !($decena==2 && $unidad>0)) $texto.=$unidades[$unidad].' ';
        }
    }
    return $texto;
}

function numeroALetrasCorto($numero){
    $unidades=['','UN','DOS','TRES','CUATRO','CINCO','SEIS','SIETE','OCHO','NUEVE'];
    $decenas=['','DIEZ','VEINTE','TREINTA','CUARENTA','CINCUENTA','SESENTA','SETENTA','OCHENTA','NOVENTA'];
    $especiales=['DIEZ','ONCE','DOCE','TRECE','CATORCE','QUINCE','DIECISEIS','DIECISIETE','DIECIOCHO','DIECINUEVE'];
    $numero=(int)$numero;
    if($numero==0) return 'CERO';
    if($numero>=10 && $numero<=19) return $especiales[$numero-10];
    $decena=floor($numero/10); $unidad=$numero%10;
    return $decena>0? $decenas[$decena].($unidad>0?' Y '.$unidades[$unidad]:'') : $unidades[$unidad];
}

$totalEnLetras = numeroALetras($totalGlobal);
@endphp

@foreach($paginas as $paginaIndex => $productosPagina)
<div class="page">
    <!-- ================= DATOS SUPERIORES ================= -->
    <div class="row">
        <div class="col w-6 box">
            <div class="label">DIA</div>
            {{ $fecha->format('d') }}
        </div>
        <div class="col w-6 box">
            <div class="label">MES</div>
            {{ $fecha->format('m') }}
        </div>
        <div class="col w-8 box">
            <div class="label">AÑO</div>
            {{ $fecha->format('Y') }}
        </div>
        <div class="col w-50 box left">
            <div class="label">Solicitante.:</div>
            {{ $header->proveedor_nombre }}
        </div>
        <div class="col w-25 box">
            <div class="label">NIT.:</div>
            {{ $header->proveedor_nit }}
        </div>
    </div>

    <div class="row">
        <div class="col w-70 box left">
            <div class="label">Dirección.:</div>
            {{ $header->proveedor_direccion }}
        </div>
        <div class="col w-30 box">
            <div class="label">Tel.:</div>
        </div>
    </div>

    <div class="separator"></div>
    <br>
    <p>
        Señor Guardalmacén, procedente del proveedor identificado en casilla anterior,
        sírvase recibir lo siguiente:
    </p>

    <!-- ================= TABLA PRINCIPAL ================= -->
    <table>
        <thead>
            <tr>
                <th width="8%">CANTIDAD</th>
                <th width="12%">UNIDAD DE<br>MEDIDA</th>
                <th>DESCRIPCIÓN DE LOS BIENES</th>
                <th width="10%">VALOR<br>UNITARIO</th>
                <th width="12%">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productosPagina as $item)
            <tr>
                <td class="center">{{ $item->cantidad }}</td>
                <td class="center">UNIDAD</td>
                <td class="descripcion">{{ $item->nombre_producto }}</td>
                <td class="right">{{ number_format($item->precio_unitario,2) }}</td>
                <td class="right">{{ number_format($item->subtotal,2) }}</td>
            </tr>
            @endforeach

            @php $filasVacias = $filasPorPagina - $productosPagina->count(); @endphp
            @for($i=0; $i<$filasVacias; $i++)
            <tr>
                <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
            </tr>
            @endfor
        </tbody>
    </table>

    <!-- ================= TOTAL ================= -->
    <table class="no-border">
        <tr>
            <td width="76%" class="right total-row">******TOTAL DE LA FACTURA******</td>
            <td width="24%" class="right total-row">{{ number_format($totalGlobal,2) }}</td>
        </tr>
    </table>

    <div class="separator"></div>

    <!-- ================= OBSERVACIONES ================= -->
    <div class="observaciones-box">
        <strong>SON:</strong> {{ $totalEnLetras }}<br><br>
        <strong>Observaciones:</strong><br>
        {{ $header->descripcion_requisicion }}
    </div>

    <div class="separator"></div>

    <!-- ================= FIRMAS ================= -->
    <div class="row">
        <div class="col w-firma firma-container">
            <span class="texto-firma">
                {{ $header->proveedor_nombre }}
                <br>Firma del Solicitante
            </span>
        </div>
        <div class="col w-firma firma-container">
            <span class="texto-firma">

                Firma del Jefe de Oficina
            </span>
        </div>
        <div class="col w-firma firma-container">
            <span class="texto-firma">
                <br>
                Firma del Guardalmacén
            </span>
        </div>
        <div class="col w-firma firma-container">
            <span class="texto-firma">
                {{ $header->proveedor_nombre }}<br>
                Firma de quien Autoriza
            </span>
        </div>
    </div>

</div> <!-- FIN DE PÁGINA -->
@endforeach

</body>
</html>
