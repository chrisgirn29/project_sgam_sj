@extends('layout.app')

@section('contents')

<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<div class="-mx-4 sm:-mx-6 lg:-mx-8 mt-6">

    <!-- ================= ENCABEZADO ================= -->
    <div class="bg-white shadow p-4 flex justify-between items-center w-full">
        <h1 class="text-2xl font-semibold text-gray-700">
            Detalle de Entregas Generadas.:
        </h1>
    </div>

    <!-- ================= TABLA ================= -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden w-full mt-4">
        <div class="px-4 py-2 font-semibold text-white shadow-sm" style="background-color:#6fae2d;">
            Entregas
        </div>

        <div class="p-4 overflow-x-auto">
            <table id="recepciones-table" class="w-full text-left border-collapse">
                <thead class="bg-gray-200 text-gray-700 text-sm uppercase">
                <tr>
                    <th class="px-2 py-2 w-20 text-center">Cod.Entrega</th>
                    <th class="px-4 py-2">Requisición</th>
                    <th class="px-4 py-2">Empleado</th>
                    <th class="px-4 py-2">Fecha</th>
                    <th class="px-4 py-2 text-right">Total</th>
                    <th class="px-4 py-2 text-center">Acciones</th>
                </tr>
                </thead>

                <tbody id="recepciones-table-body" class="text-sm text-gray-700"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= LIBRERÍAS ================= -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- ================= SCRIPTS ================= -->
<script>
let entregasTable;

document.addEventListener('DOMContentLoaded', function () {

    entregasTable = $('#recepciones-table').DataTable({
        ajax: {
            url: "{{ route('entregas.getListado') }}",
            dataSrc: function (json) {
                console.log('JSON:', json);
                return json.data ?? json;
            }
        },
        columns: [
            { data: 'id_entrega', className: 'text-center' },
            { data: 'id_requisicion' },
            { data: 'nombre_completo' },
            {
                data: 'fecha_entrega',
                render: function (data) {
                    return data
                        ? new Date(data).toLocaleDateString('es-GT')
                        : '-';
                }
            },
            {
                data: 'total',
                className: 'text-right font-semibold',
                render: function (data) {
                    return 'Q ' + Number(data).toFixed(2);
                }
            },
            {
                data: null,
                orderable: false,
                className: 'text-center',
                render: function (data) {
                    return `
                        <button
                            class="text-orange-600 hover:text-orange-800 btn-detalle"
                            data-entrega="${data.id_entrega}"
                            data-requisicion="${data.id_requisicion}"
                            title="Ver Entrega">
                            <i class="fa-sharp fa-solid fa-clipboard-list"></i>
                        </button>


                    `;
                }
            }
        ],
        order: [[3, 'desc']],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json"
        }
    });

});
$(document).on('click', '.btn-detalle', function () {

    const idEntrega     = $(this).data('entrega');
    const idRequisicion = $(this).data('requisicion');

    if (!idEntrega || !idRequisicion) {
        console.error('Datos incompletos', idEntrega, idRequisicion);
        return;
    }

    const url = `/impresion/entrega/${idEntrega}/${idRequisicion}`;

    window.open(url, '_blank');
});
</script>


@endsection
