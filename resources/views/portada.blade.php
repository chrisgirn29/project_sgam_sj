@extends('layout.app')

@section('contents')

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Principal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>

    </style>
</head>

<body class="bg-slate-950 min-h-screen">

<div class="min-h-screen flex items-start justify-center px-6 pt-4">

    <!-- CONTENEDOR CON IMAGEN SUBIDA HACIA EL HEADER -->
    <div
        class="w-full max-w-7xl h-[640px] rounded-3xl overflow-hidden glow
               bg-cover bg-no-repeat"
        style="
            background-image: url('{{ asset('fondo4.jpg') }}');
            background-position: center -80px;
        "
    >
        <!-- solo imagen -->
    </div>

</div>

</body>
</html>

@endsection
