@extends('layout.app')

@section('contents')

<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<div class="-mx-4 sm:-mx-6 lg:-mx-8 mt-6">

    <!-- ================= ENCABEZADO ================= -->
    <div class="bg-white shadow p-4 flex justify-between items-center w-full">
        <h1 class="text-2xl font-semibold text-gray-700">
            Detalle de Recepciones Generadas.:
        </h1>
    </div>

    <!-- ================= TABLA ================= -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden w-full mt-4">
        <div class="px-4 py-2 font-semibold text-white shadow-sm" style="background-color:#6fae2d;">
            Recepciones
        </div>

        <div class="p-4 overflow-x-auto">
            <table id="recepciones-table" class="w-full text-left border-collapse">
                <thead class="bg-gray-200 text-gray-700 text-sm uppercase">
                    <tr>
                        <th class="px-2 py-2 w-20 text-center whitespace-nowrap">Cod.Recepcion</th>
                        <th class="px-4 py-2">Req.</th>
                        <th class="px-4 py-2">Serie</th>
                        <th class="px-4 py-2">Factura</th>
                        <th class="px-4 py-2">Documento</th>
                        <th class="px-4 py-2">Proveedor</th>
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
let recepcionesTable;

function loadRecepciones() {
    fetch("{{ route('recepciones.getListado') }}")
        .then(res => res.json())
        .then(data => {

            if (recepcionesTable) {
                recepcionesTable.clear().destroy();
            }

            const tbody = document.getElementById('recepciones-table-body');
            tbody.innerHTML = '';

            data.forEach(r => {

                const fecha = new Date(r.fecha_recepcion).toLocaleDateString('es-GT');

                const acciones = `
                    <button
                        class="text-orange-600 hover:text-orange-800 btn-detalle"
                        data-recepcion="${r.id_recepcion}"
                        data-requisicion="${r.id_requisicion}"
                        title="Ver detalle">
                        <i class="fa-solid fa-list"></i>
                    </button>

                `;

                tbody.innerHTML += `
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">${r.id_recepcion}</td>
                        <td class="px-4 py-2">${r.id_requisicion}</td>
                        <td class="px-4 py-2">${r.serie_factura ?? '-'}</td>
                        <td class="px-4 py-2">${r.numero_factura ?? '-'}</td>
                        <td class="px-4 py-2">${r.numero_documento ?? '-'}</td>
                        <td class="px-4 py-2">${r.proveedor}</td>
                        <td class="px-4 py-2">${fecha}</td>
                        <td class="px-4 py-2 text-right font-semibold">
                            Q ${parseFloat(r.total ?? 0).toFixed(2)}
                        </td>
                        <td class="px-4 py-2 text-center">${acciones}</td>
                    </tr>
                `;
            });

            recepcionesTable = $('#recepciones-table').DataTable({
                order: [[5, 'desc']],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json"
                }
            });
        })
        .catch(err => console.error('Error cargando recepciones:', err));
}
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-detalle');
    if (!btn) return;

    const idRecepcion   = btn.dataset.recepcion;
    const idRequisicion = btn.dataset.requisicion;

    if (!idRecepcion || !idRequisicion) {
        console.error('IDs no encontrados', btn.dataset);
        return;
    }

    window.open(
        `/impresion/recepcion/${idRecepcion}/${idRequisicion}`,
        '_blank'
    );
});


document.addEventListener('DOMContentLoaded', loadRecepciones);
</script>

@endsection
