@extends('layout.app')

@section('contents')

<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<div class="-mx-4 sm:-mx-6 lg:-mx-8 mt-6">

    <!-- ================= ENCABEZADO ================= -->
    <div class="bg-white shadow p-4 flex justify-between items-center w-full">
        <h1 class="text-2xl font-semibold text-gray-700">
            Detalle de los CDP emitidos
        </h1>
    </div>

    <!-- ================= TABLA ================= -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden w-full mt-4">
        <div class="px-4 py-2 font-semibold text-white shadow-sm" style="background-color:#6fae2d;">
            Listado de CDP
        </div>

        <div class="p-4">
            <table id="categorias-table" class="w-full text-left border-collapse">
                <thead class="bg-gray-200 text-gray-700 text-sm uppercase">
                    <tr>
                        <th class="px-4 py-2">ID CDP</th>
                        <th class="px-4 py-2">Descripción</th>
                        <th class="px-4 py-2">Empleado</th>
                        <th class="px-4 py-2">Fecha</th>
                        <th class="px-4 py-2">Monto</th>
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

/* ================= CARGAR CDP ================= */
function loadCategorias() {
    fetch("{{ route('cdp.getListado') }}")
        .then(res => res.json())
        .then(data => {

            if (categoriasTable) {
                categoriasTable.clear().destroy();
            }

            const tbody = document.getElementById('categorias-table-body');
            tbody.innerHTML = '';

            data.forEach(r => {

                const acciones = `
                    <button
                        class="text-orange-600 hover:text-orange-800 btn-detalle flex items-center gap-1"
                        data-id="${r.id_cdp}"
                        title="Ver CDP">
                        <i class="fa-solid fa-book"></i>
                    </button>
                `;

                tbody.innerHTML += `
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">${r.id_cdp}</td>
                        <td class="px-4 py-2">${r.descripcion}</td>
                        <td class="px-4 py-2">${r.empleado}</td>
                        <td class="px-4 py-2">${r.fecha}</td>
                        <td class="px-4 py-2">
                            Q ${parseFloat(r.total).toFixed(2)}
                        </td>
                        <td class="px-4 py-2">${acciones}</td>
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

/* ================= BOTÓN VER DETALLE ================= */
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.btn-detalle');
    if (btn) {
        const id = btn.dataset.id;
        window.open(`/cdp/${id}/pdf`, '_blank');
    }
});

/* ================= INIT ================= */
document.addEventListener('DOMContentLoaded', loadCategorias);
</script>

@endsection
