@extends('layout.app')

@section('contents')

<!-- ================= TAILWIND ================= -->
<script src="https://cdn.tailwindcss.com"></script>

<script>
tailwind.config = {
  theme: {
    extend: {
      colors: {
        primary: '#6fae2d',
        primarySoft: '#eaf4df',
        bgSoft: '#f4f6f2'
      }
    }
  }
}
</script>

<div class="-mx-4 sm:-mx-6 lg:-mx-8 bg-bgSoft min-h-screen">

    <!-- CONTENEDOR PRINCIPAL -->
    <div class="w-full px-8">

        <!-- ================= HEADER ================= -->
        <header class="border-b-4 border-primary bg-white">
            <div class="py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold tracking-wide uppercase">
                    Kardex de Inventario
                </h1>
                <div class="w-12 h-12 bg-primary text-white flex items-center justify-center text-lg">
                    INV
                </div>
            </div>
        </header>

        <!-- ================= FILTROS (VISUAL) ================= -->
        <section class="mt-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <div class="bg-white border-2 border-gray-200 p-4">
                    <p class="text-xs uppercase text-gray-500 mb-1">Fecha inicial</p>
                    <input type="date"
                        class="w-full border-2 border-gray-300 px-3 py-2 focus:outline-none focus:border-primary">
                </div>

                <div class="bg-white border-2 border-gray-200 p-4">
                    <p class="text-xs uppercase text-gray-500 mb-1">Fecha final</p>
                    <input type="date"
                        class="w-full border-2 border-gray-300 px-3 py-2 focus:outline-none focus:border-primary">
                </div>

                <div class="bg-white border-2 border-gray-200 p-4">
                    <p class="text-xs uppercase text-gray-500 mb-1">Tipo</p>
                    <select
                        class="w-full border-2 border-gray-300 px-3 py-2 focus:outline-none focus:border-primary">
                        <option>Inventario</option>
                        <option>Entradas</option>
                        <option>Salidas</option>
                    </select>
                </div>

                <div class="bg-white border-2 border-gray-200 p-4 flex gap-2 items-end">
                    <button class="flex-1 bg-primary text-white py-2 font-semibold">
                        Consultar
                    </button>
                    <button onclick="window.open('{{ route('kardex.pdf', $id_producto) }}','_blank')"
        class="border-2 border-gray-300 px-3 py-2">
    🖨️
</button>



                    <button onclick="window.history.back()"
                            class="border-2 border-gray-300 px-3 py-2">
                        ←
                    </button>
                </div>

            </div>
        </section>

        <!-- ================= TABLA KARDEX ================= -->
        <section class="mt-6 pb-16">

            <!-- ================= INFO DEL PRODUCTO ================= -->
<div class="bg-white border-4 border-gray-300 mb-4">

    <div class="grid grid-cols-12 text-sm font-semibold uppercase">

        <!-- ARTÍCULO -->
        <div class="col-span-6 border-r-2 border-gray-300 p-3 flex gap-2">
            <span>Artículo:</span>
            <span class="flex-1 border-b border-black">
                {{ $producto->nombre }}
            </span>
        </div>

        <!-- UNIDAD DE MEDIDA -->
        <div class="col-span-4 border-r-2 border-gray-300 p-3 flex gap-2">
            <span>Unidad de medida:</span>
            <span class="flex-1 border-b border-black">
                {{ $producto->medida }}
            </span>
        </div>

        <!-- CLAVE -->
        <div class="col-span-2 p-3 flex gap-2">
            <span>Codigo:</span>
            <span class="flex-1 border-b border-black">
                {{ $producto->id_producto }}
            </span>
        </div>

    </div>

</div>



            <div id="kardex-print" class="bg-white border-4 border-gray-300 w-full">


                <!-- HEADER PRINCIPAL -->
                <div class="grid grid-cols-12 text-xs uppercase font-semibold border-b-4 border-gray-300 bg-gray-100">
                    <div class="col-span-2 p-3 border-r-2">Proveedor / Destino</div>
                    <div class="col-span-1 p-3 border-r-2">Fecha</div>
                    <div class="col-span-4 p-3 border-r-2 text-center bg-primarySoft">Entradas</div>
                    <div class="col-span-1 p-3 border-r-2">Fecha</div>
                    <div class="col-span-3 p-3 border-r-2 text-center bg-primarySoft">Salidas</div>
                    <div class="col-span-1 p-3 text-center bg-primarySoft">Saldo</div>
                </div>

                <!-- SUBHEADER -->
                <div class="grid grid-cols-12 text-xs text-center border-b-2 border-gray-300 bg-gray-50">
                    <div class="col-span-3"></div>

                    <div class="p-2 border-r">Factura</div>
                    <div class="p-2 border-r">Cant</div>
                    <div class="p-2 border-r">V. Unit</div>
                    <div class="p-2 border-r">Total</div>

                    <div></div>

                    <div class="p-2 border-r">Salida</div>
                    <div class="p-2 border-r">Cant</div>
                    <div class="p-2 border-r">Total</div>

                    <div class="p-2">Saldo</div>
                </div>

                <!-- FILAS -->
                @forelse($kardex as $k)
                <div class="grid grid-cols-12 text-sm border-b-2 border-gray-200 hover:bg-primarySoft/50">

                    <div class="col-span-2 p-3 border-r-2 font-semibold">
                        {{ $k->proveedor_destino }}
                    </div>

                    <div class="col-span-1 p-3 border-r-2">
                        {{ $k->fecha_entrada ?? $k->fecha_evento }}
                    </div>

                    <div class="p-3 border-r-2">
                        {{ $k->numero_factura }}
                    </div>

                    <div class="p-3 border-r-2 text-center text-primary font-semibold">
                        {{ $k->cantidad_entrada ?: '' }}
                    </div>

                    <div class="p-3 border-r-2">
                        {{ $k->valor_unitario_entrada ? 'Q '.number_format($k->valor_unitario_entrada,2) : '' }}
                    </div>

                    <div class="p-3 border-r-2 font-semibold">
                        {{ $k->valor_total_entrada ? 'Q '.number_format($k->valor_total_entrada,2) : '' }}
                    </div>

                    <div class="col-span-1 p-3 border-r-2">
                        {{ $k->no_salida ? $k->fecha_evento : '' }}
                    </div>

                    <div class="p-3 border-r-2">
                        {{ $k->no_salida }}
                    </div>

                    <div class="p-3 border-r-2 text-center text-red-600 font-semibold">
                        {{ $k->cantidad_salida ?: '' }}
                    </div>

                    <div class="p-3 border-r-2">
                        {{ $k->valor_total_salida ? 'Q '.number_format($k->valor_total_salida,2) : '' }}
                    </div>

                    <div class="p-3 text-center font-bold text-primary">
                        {{ $k->saldo_cantidad }}
                    </div>

                </div>
                @empty
                <div class="p-6 text-center text-gray-500">
                    No hay movimientos para este producto.
                </div>
                @endforelse

            </div>

        </section>

    </div>
</div>

<script>
function printKardex() {

    const element = document.getElementById('kardex-print');

    const opt = {
        margin:       [3.5, 2, 2, 2], // top, left, bottom, right (cm)
        filename:     'kardex_inventario.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, useCORS: true },
        jsPDF: {
            unit: 'cm',
            format: 'legal',
            orientation: 'landscape'
        },
        pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
    };

    html2pdf()
        .set(opt)
        .from(element)
        .toPdf()
        .get('pdf')
        .then(function (pdf) {
            const blobUrl = pdf.output('bloburl');
            window.open(blobUrl, '_blank');
        });
}
</script>

@endsection
