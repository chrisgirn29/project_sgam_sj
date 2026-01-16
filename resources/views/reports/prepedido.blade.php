<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud Administrativa</title>

    <style>
        @page {
            size: Letter;
            margin: 3.5cm 2.5cm 3.8cm 2.5cm;
        }
 .recuadro-general {
        border: 2px solid #1f5d3a;
        border-radius: 14px;
        padding: 12px;
        margin-bottom: 16px;
        box-sizing: border-box;
    }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            color: #2b2b2b;
            background: #fff;
        }
        .bg-green-strong {
    background: #1f5d3a;   /* verde fuerte */
    color: #ffffff;        /* letras blancas */
}

.box-altura {
    min-height: 120px;   /* ajusta a gusto: 120 / 140 / 160 */
}

        /* ================= ENCABEZADO ================= */
        header {
            position: fixed;
            top: -11cm;
            left: -5cm;
            width: calc(100% + 10cm);
            height: 200px;
        }

        header img {
            width: 100%;
            display: block;
        }

        /* ================= PIE ================= */
        footer {
            position: fixed;
            bottom: 0;
            left: -5cm;
            width: calc(100% + 10cm);
            height: 240px;
        }

        footer img {
            width: 100%;
            display: block;
        }

        /* ================= CONTENEDOR FULL WIDTH ================= */
    .full-width {
    position: relative;
    width: 110%;
    left: -5%;
    box-sizing: border-box;
}




        /* ================= CONTENIDO ================= */
        .content-start {
            margin-top: 30px;
        }

        .box {
            border: 1px solid #c8d2c2;
            margin-bottom: 14px;
            box-sizing: border-box;
            border-radius: 10px;          /* BORDES REDONDOS */
            overflow: hidden;             /* CLAVE para tablas internas */
        }


        .title {
            font-size: 22px;
            font-weight: bold;
            color: #1f5d3a;
        }

        .subtitle {
            font-size: 11px;
            text-transform: uppercase;
            color: #2f7a4f;
        }

        .bg-green { background: #e2ead6; }
        .bg-yellow { background: #B1D42F; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            border-top: 1px solid #c8d2c2;
            font-size: 12px;
        }

        th {
            text-align: left;
        }

        .firma {
            margin-top: 60px;
            text-align: center;
        }


/* ================= MANEJO DE SALTOS DE PÁGINA ================= */

        /* Cada página */
        .page {
            page-break-after: always;
        }

        /* Primera página */
        .page:first-child .content-start {
            margin-top: 30px;
        }

        /* Segunda página en adelante */
        .page:not(:first-child) .content-start {
            margin-top: 170px; /* 👈 ESPACIO BAJO EL ENCABEZADO */
        }

            table {
    page-break-inside: auto;
}

tr {
    page-break-inside: avoid;
    page-break-after: auto;
}




    </style>
</head>

<body>

<!-- ENCABEZADO -->
<header>
    <img src="{{ public_path('encabezado.png') }}">
</header>

<!-- PIE -->
<footer>
    <img src="{{ public_path('pie_pagina.png') }}">
</footer>

<!-- ================= CONTENIDO ================= -->

<div class="page">

    <div class="full-width content-start">

        <!-- ===== RECUADRO GENERAL ===== -->
        <div class="recuadro-general">

            <div class="box">
                <table>
                    <tr>
                        <td width="5%" style="background:#1f5d3a;"></td>
                        <td width="65%" class="bg-green">
                            <div class="title">PRE-PEDIDO</div>
                            <div class="subtitle">Documento Guardalmacen</div>
                        </td>
                        <td width="30%" class="bg-yellow">
                            <span style="font-size:22px;">
                                No. {{ str_pad($id_requisicion, 6, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="box">
                <table>
                    <tr>
                        <td width="50%">
                            <strong>Datos del solicitante</strong><br><br>
                            <strong>Nombre:</strong> {{ $nombre ?? '—' }}<br>
                            <strong>Área:</strong> {{ $area ?? '—' }}<br>
                            <strong>Puesto:</strong> {{ $puesto ?? '—' }}
                        </td>
                        <td width="50%">
                            <strong>Información del registro.:</strong><br><br>
                            <strong>Fecha:</strong> {{ now()->format('d/m/Y H:i') }}<br>
                            <strong>Programa:</strong> {{ $programa ?? '—' }}
                        </td>
                    </tr>
                </table>
            </div>

            <div class="box bg-green" style="padding:10px;">
                <strong>Observaciones.:</strong><br><br>
                {{ $observaciones ?? '' }}
            </div>

        </div>
        <!-- ===== FIN RECUADRO GENERAL ===== -->

        <div class="box">
            <div class="bg-green-strong" style="padding:8px;">
                <strong>Detalle de artículos.:</strong>
            </div>

            <table>
                <thead>
                    <tr>
                        <th width="10%">Item</th>
                        <th width="70%">Descripción</th>
                        <th width="20%" style="text-align:center;">Cantidad</th>

                    </tr>
                </thead>
                <tbody>
                    @forelse($detalles as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nombre_producto }}</td>
                            <td style="text-align:center;">
                                {{ number_format($item->cantidad, 2) }}
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" align="center">No hay productos registrados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="firma">
            <br>
            <span>{{ $nombre ?? 'Nombre del Firmante' }}</span><br>
            <span>{{ $puesto ?? 'Cargo del Firmante' }}</span>
        </div>

    </div>

</div>



</body>
</html>
