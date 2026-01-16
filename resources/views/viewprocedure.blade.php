@extends('layout.app')

@section('contents')

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Expediente</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Color corporativo -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        corporate: '#6fae2d'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100 py-10">

<div class="-mx-4 sm:-mx-6 lg:-mx-8 w-auto bg-white rounded-xl shadow-sm border border-gray-200 p-8">

<!--<div class="-mx-4 sm:-mx-6 lg:-mx-8 mt-6">->
    <!-- HEADER -->
    <div class="flex justify-between items-center border-b pb-6 mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">
    Expediente
    <span class="text-gray-500">
        #EXP-{{ $id_requisicion }}
    </span>
</h1>

            <p class="text-sm text-gray-500 mt-1">
                <strong>Unidad y/o Programa solicitante:</strong>
                <br><span class="font-medium text-gray-700">
                    {{ $nombrePrograma }}
                </span>
            </p>
        </div>

        <span class="px-4 py-1 text-sm font-medium rounded-full
                     bg-blue-50 text-blue-700 border border-blue-200">
            En proceso
        </span>
    </div>

    <!-- TIMELINE -->
    <div class="relative">

        <!-- Línea -->
        <div class="absolute left-4 top-0 bottom-0 w-px bg-gray-300 pointer-events-none"></div>


        <!-- SOLICITUD -->
        <div class="flex gap-6 mb-10 relative">
            <div class="relative z-10">
                <div class="w-8 h-8 rounded-full bg-corporate
                            flex items-center justify-center text-white text-sm">
                    ✓
                </div>
            </div>

            <div class="flex-1 flex justify-between items-center">
                <div>
                    <h3 class="font-semibold text-gray-800">Solicitud</h3>
                    <p class="text-sm text-gray-500">
                        Solicitud creada por el usuario
                    </p>

                    <a href="{{ route('solicitud.pdf', $id_requisicion) }}"
                    target="_blank"
                    class="mt-2 inline-flex items-center gap-2 px-4 py-1.5 text-sm font-semibold
                            rounded-md bg-[#F5A927] text-white
                            border border-[#F5A927]
                            hover:bg-[#f7b84a]
                            active:bg-white active:text-[#F5A927]
                            active:scale-95
                            transition-all duration-200 shadow-sm hover:shadow-md">
                        Ver PDF
                    </a>




                </div>

                <span class="text-xs text-gray-400 font-medium">
                    SOL-2026-0045
                </span>
            </div>
        </div>

        <!-- PREPEDIDO -->
        <div class="flex gap-6 mb-10 relative">
            <div class="relative z-10">
                <div class="w-8 h-8 rounded-full bg-corporate
                            flex items-center justify-center text-white text-sm">
                    ✓
                </div>
            </div>

            <div class="flex-1 flex justify-between items-center">
                <div>
                    <h3 class="font-semibold text-gray-800">Prepedido</h3>
                    <p class="text-sm text-gray-500">
                        Productos agregados y confirmados
                    </p>

                    <a href="{{ route('requisiciones.prepedido.pdf', $id_requisicion) }}"
                    target="_blank"
                        class="mt-2 inline-flex items-center gap-2 px-4 py-1.5 text-sm font-semibold
                            rounded-md bg-[#F5A927] text-white
                            border border-[#F5A927]
                            hover:bg-[#f7b84a]
                            active:bg-white active:text-[#F5A927]
                            active:scale-95
                            transition-all duration-200 shadow-sm hover:shadow-md">
                        Ver PDF
                    </a>

                </div>

                <span class="text-xs text-gray-400 font-medium">
                    PRE-2026-0045
                </span>
            </div>
        </div>

        <!-- REQUISICIÓN -->
        <div class="flex gap-6 mb-10 relative">
            <div class="relative z-10">
                <div class="w-8 h-8 rounded-full bg-corporate
                            flex items-center justify-center text-white text-sm">
                    ✓
                </div>
            </div>

            <div class="flex-1 flex justify-between items-center">
                <div>
                    <h3 class="font-semibold text-gray-800">Requisición</h3>
                    <p class="text-sm text-gray-500">
                        Requisición generada correctamente
                    </p>

                    <a href="{{ route('requisiciones.requisicion.pdf', $id_requisicion) }}"
                    target="_blank"
                    class="mt-2 inline-flex items-center gap-2 px-4 py-1.5 text-sm font-semibold
                            rounded-md bg-[#F5A927] text-white
                            border border-[#F5A927]
                            hover:bg-[#f7b84a]
                            active:bg-white active:text-[#F5A927]
                            active:scale-95
                            transition-all duration-200 shadow-sm hover:shadow-md">
                        Ver PDF
                    </a>

                </div>

                <span class="text-xs text-gray-400 font-medium">
                    REQ-2026-0045
                </span>
            </div>
        </div>

        <!-- AUTORIZACIÓN -->
        <div class="flex gap-6 relative">
            <div class="relative z-10">
                <div class="w-8 h-8 rounded-full bg-corporate
                            flex items-center justify-center text-white text-sm">
                    ✓
                </div>
            </div>

            <div class="flex-1 flex justify-between items-center">
                <div>
                    <h3 class="font-semibold text-gray-800">Autorización</h3>
                    <p class="text-sm text-gray-500">
                        Pendiente de revisión por el autorizador
                    </p>

                    <div class="flex gap-3 mt-3">
                       <a href="{{ route('autorizacion.ver', $id_requisicion) }}"
                       target="_blank"
                       class="mt-2 inline-flex items-center gap-2 px-4 py-1.5 text-sm font-semibold
                            rounded-md bg-[#F5A927] text-white
                            border border-[#F5A927]
                            hover:bg-[#f7b84a]
                            active:bg-white active:text-[#F5A927]
                            active:scale-95
                            transition-all duration-200 shadow-sm hover:shadow-md">
                            Ver PDF
                        </a>
                    </div>
                </div>

                <span class="text-xs text-gray-400 font-medium">
                    AUT-2026-0045
                </span>
            </div>
        </div>


    </div>

</div>

</body>
</html>

@endsection
