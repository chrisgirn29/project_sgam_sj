    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Oficio - Impresión</title>

        <style>
            @page {
                size: Letter;
                margin: 3.5cm 2.5cm 3.8cm 2.5cm;
            }

            html, body {
                height: 100%;
            }

            body {
                font-family: "Times New Roman", serif;
                font-size: 12pt;
                margin: 0;
                padding: 0;
                background: #fff;
                color: #000;
            }

            /* ================= ENCABEZADO ================= */
    header {
        position: fixed;
        top: -11.0cm;          /* ← sube más */
        left: -5.0cm;
        width: calc(100% + 10cm);
        height: 200px;
    }



            header img {
                width: 100%;
                height: auto;
                display: block;
            }

            /* ================= PIE DE PAGINA ================= */
        footer {
        position: fixed;
        bottom: 0;
        left: -5.0cm;
        width: calc(100% + 10cm);
        height: 240px;
    }


            footer img {
                width: 100%;
                height: auto;
                display: block;
            }

            /* ================= CONTENIDO ================= */
            .document {
                width: 100%;
            }

            .oficio-info {
                text-align: right;
                margin-bottom: 25px;
                page-break-inside: avoid;
            }

            .document p {
                margin: 0 0 18px 0;
                text-align: justify;
            }

            /* ================= FIRMA ================= */
            .firma {
                margin-top: 270px;
                text-align: center;
                page-break-inside: avoid;
            }

            .firma span {
                display: block;
                margin-top: 5px;
                font-weight: bold;
            }

            .cc {
                margin-top: 40px;
                font-size: 11pt;
                page-break-inside: avoid;
            }




    /* DEGRADADO TIPO WORD */
    header::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;

        background: linear-gradient(
            to bottom,
            rgba(255,255,255,0) 0%,
            rgba(255,255,255,0.15) 40%,
            rgba(255,255,255,0.35) 70%,
            rgba(255,255,255,0.6) 100%
        );
    }

    footer::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;

        background: linear-gradient(
            to top,
            rgba(255,255,255,0) 0%,
            rgba(255,255,255,0.15) 40%,
            rgba(255,255,255,0.35) 70%,
            rgba(255,255,255,0.6) 100%
        );
    }


        </style>
    </head>

    <body>

        <!-- ================= ENCABEZADO ================= -->
        <header>
            <img src="{{ public_path('encabezado.png') }}" alt="Encabezado">
        </header>

        <!-- ================= PIE DE PAGINA ================= -->
        <footer>
            <img src="{{ public_path('pie_pagina.png') }}" alt="Pie de página">
        </footer>

        <!-- ================= DOCUMENTO ================= -->
        <div class="document">

            <div class="oficio-info">
            <br>  <strong>Solicitud No.{{ str_pad($id_requisicion, 6, '0', STR_PAD_LEFT) }}</strong><br>

                {{ $lugar ?? 'San Jerónimo, Baja Verapaz' }},
                {{ $fecha ?? now()->format('d \d\e F \d\e Y') }}
            </div>

            <p>
                <br><strong>M.A. Moises Roman Canahui Morente</strong><br>
                <strong>Alcalde Municipal</strong><br>
                <strong>Municipalidad de {{ $municipio ?? 'San Jerónimo' }}, B.V.</strong>
            </p>

            <p><strong>Presente:</strong></p>

            <p>
                De la manera mas atenta me dirijo a usted, deseandole exito y bendiciones en cada una  de las
                actividades que realiza en beneficio de la población del Municipio de San Jerónimo.
            </p>

            <p>
                El motivo de la presente es solicitar su valiosa colaboración para la
                <strong>AUTORIZACIÓN</strong> de
                <span>{{ $descripcion }}</span>
            </p>


            <p>
                Agradeciéndo desde ya su fina atención a la presente,
                sin otro particular me despido de usted.
            </p>

            <p><strong>Atentamente,</strong></p>

        <div class="firma">
        <span>{{ $firmante ?? 'Nombre del Firmante' }}</span>
        <span>{{ $cargoFirmante ?? 'Cargo del Firmante' }}</span>
    </div>




        </div>

    </body>
    </html>
