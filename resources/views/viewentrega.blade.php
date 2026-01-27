@extends('layout.app')

@section('contents')

<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<div class="-mx-4 sm:-mx-6 lg:-mx-8 mt-6">

    <!-- ================= ENCABEZADO ================= -->
    <div class="bg-white shadow p-4 flex justify-between items-center w-full">
        <h1 class="text-2xl font-semibold text-gray-700">
            Registro de Despachos y/o Entregas.:
        </h1>
    </div>

    <!-- ================= TABLA ================= -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden w-full mt-4">
        <div class="px-4 py-2 font-semibold text-white shadow-sm" style="background-color:#6fae2d;">
            Despachos
        </div>

        <div class="p-4">
            <table id="categorias-table" class="w-full text-left border-collapse">
                <thead class="bg-gray-200 text-gray-700 text-sm uppercase">
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Empleado</th>
                        <th class="px-4 py-2">Fecha</th>
                        <th class="px-4 py-2">Total</th>
                        <th class="px-4 py-2 text-center">P / Pendientes</th>
                        <th class="px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody id="categorias-table-body" class="text-sm text-gray-700"></tbody>
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
let categoriasTable;

/* ================= CARGAR REQUISICIONES ================= */
function loadCategorias() {
    fetch("{{ route('requisiciones.getListados') }}")
        .then(res => res.json())
        .then(data => {

            if (categoriasTable) {
                categoriasTable.clear().destroy();
            }

            const tbody = document.getElementById('categorias-table-body');
            tbody.innerHTML = '';

            data.forEach(r => {

                /* ================= PENDIENTES ================= */
                let badgePendiente = '';

                if (parseInt(r.pendientes) === 0) {
                    badgePendiente = `
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                            0
                        </span>`;
                } else {
                    badgePendiente = `
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-700">
                            ${r.pendientes}
                        </span>`;
                }

                const acciones = `
                    <button
                        class="text-orange-600 hover:text-orange-800 btn-detalle flex items-center gap-1"
                        data-id="${r.id_requisicion}"
                        title="Entregar">
                        <i class="fa-solid fa-truck"></i>
                    </button>
                `;

                tbody.innerHTML += `
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">${r.id_requisicion}</td>
                        <td class="px-4 py-2">${r.empleado}</td>
                        <td class="px-4 py-2">${r.fecha}</td>
                        <td class="px-4 py-2">
                            Q ${parseFloat(r.total).toFixed(2)}
                        </td>
                        <td class="px-4 py-2 text-center">
                            ${badgePendiente}
                        </td>
                        <td class="px-4 py-2">
                            ${acciones}
                        </td>
                    </tr>
                `;
            });

            categoriasTable = $('#categorias-table').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json"
                }
            });
        });
}

/* ================= BOTÓN RECEPCIONAR ================= */
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.btn-detalle');
    if (btn) {
        const id = btn.dataset.id;
        window.location.href = `/requisiciones/${id}/despacho`;

    }
});

/* ================= INIT ================= */
document.addEventListener('DOMContentLoaded', loadCategorias);
</script>

@endsection
