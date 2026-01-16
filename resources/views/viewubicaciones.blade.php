@extends('layout.app')

@section('contents')
<div class="-mx-4 sm:-mx-6 lg:-mx-8 mt-6">

    <!-- Encabezado -->
    <div class="bg-white shadow p-4 flex justify-between items-center w-full">
        <div>
            <h1 class="text-2xl font-semibold text-gray-700">
                Administración de Ubicaciones.:
            </h1>
        </div>
        <button onclick="openModalUbicacion()"
            class="bg-[#6fae2d] hover:bg-[#6fae2d] text-white px-4 py-2 text-sm font-semibold rounded-lg shadow-md flex items-center gap-2">
            <i class="fas fa-plus"></i>
            Agregar nueva Ubicación.:
        </button>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden w-full mt-4">
        <div class="px-4 py-2 font-semibold text-white  shadow-sm" style="background-color:#6fae2d;">UBICACIONES</div>
        <div class="p-4">
        <div class="p-4">
            <table id="ubicaciones-table" class="w-full text-left border-collapse">
                <thead class="bg-gray-200 text-gray-700 text-sm uppercase">
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Descripción</th>
                        <th class="px-4 py-2">Estado</th>
                        <th class="px-4 py-2">Usuario</th>
                        <th class="px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody id="ubicaciones-table-body" class="text-sm text-gray-700"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal CREAR/EDITAR UBICACION -->
<div id="modalUbicacion" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg w-96 p-6">
        <div class="flex justify-between items-center border-b pb-2 mb-4">
            <h2 id="modalTitle" class="text-lg font-semibold text-gray-700">Registrar Ubicación</h2>
            <button onclick="closeModalUbicacion()" class="text-gray-500 hover:text-red-500 text-xl">&times;</button>
        </div>
        <form id="formUbicacion" class="space-y-4">
            @csrf
            <input type="hidden" name="id_ubicacion" id="ubicacionId">

            <div>
                <label class="block text-sm font-medium text-gray-600">Descripción</label>
                <input type="text" name="descripcion" id="descripcion" required
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>

            <!-- CORRECCIÓN: estado inicia en ACTIVO -->
            <input type="hidden" name="estado" id="estado" value="activo">
            <input type="hidden" name="usuario" value="{{ auth()->user()->name }}">

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModalUbicacion()"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">Cancelar</button>
                <button type="submit"
                        class="bg-[#6fae2d] hover:bg-[#6fae2d] text-white px-4 py-2 rounded">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
let ubicacionesTable;

function openModalUbicacion(edit = false, ubicacion = null) {
    document.getElementById('formUbicacion').reset();
    document.getElementById('ubicacionId').value = '';
    document.getElementById('modalTitle').textContent = edit ? 'Editar Ubicación' : 'Registrar Ubicación';

    if (edit && ubicacion) {
        document.getElementById('ubicacionId').value = ubicacion.id_ubicacion;
        document.getElementById('descripcion').value = ubicacion.descripcion;
        document.getElementById('estado').value =
            (ubicacion.estado == 1 || ubicacion.estado === 'activo')
                ? 'activo'
                : 'inactivo';
    }
    document.getElementById('modalUbicacion').classList.remove('hidden');
}

function closeModalUbicacion() {
    document.getElementById('modalUbicacion').classList.add('hidden');
}

function loadUbicaciones() {
    fetch("{{ route('ubicaciones.getAll') }}")
        .then(res => res.json())
        .then(data => {
            if (ubicacionesTable) ubicacionesTable.clear().destroy();
            const tbody = document.getElementById('ubicaciones-table-body');
            tbody.innerHTML = '';

            data.forEach(u => {
                const estadoLabel =
                    (u.estado == 1 || u.estado === 'activo')
                        ? `<span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Activo</span>`
                        : `<span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Inactivo</span>`;

                const acciones = `
                    <div class="flex gap-2">
                        <button class="text-orange-600 hover:text-yellow-800 btn-edit"
                                data-ubicacion='${JSON.stringify(u)}' title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="text-yellow-600 hover:text-orange-800 btn-toggle"
                                data-id="${u.id_ubicacion}" title="Cambiar estado">
                            <i class="fas fa-exchange-alt"></i>
                        </button>
                    </div>
                `;

                tbody.innerHTML += `
                    <tr>
                        <td>${u.id_ubicacion}</td>
                        <td>${u.descripcion}</td>
                        <td>${estadoLabel}</td>
                        <td>${u.usuario}</td>
                        <td>${acciones}</td>
                    </tr>`;
            });

            ubicacionesTable = $('#ubicaciones-table').DataTable();
        });
}

document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-edit')) {
        const ubicacion = JSON.parse(e.target.closest('.btn-edit').dataset.ubicacion);
        openModalUbicacion(true, ubicacion);
    }

    if (e.target.closest('.btn-toggle')) {
        const id = e.target.closest('.btn-toggle').dataset.id;
        fetch(`/toggle-estado/${id}`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            loadUbicaciones();
            Swal.fire({
                icon: 'success',
                title: 'Estado actualizado',
                text: `El estado ahora es: ${(data.estado == 1 || data.estado === 'activo') ? 'Activo' : 'Inactivo'}`,
                timer: 1500,
                showConfirmButton: false
            });
        });
    }
});

document.getElementById('formUbicacion').addEventListener('submit', e => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const id = document.getElementById('ubicacionId').value;
    const url = id ? `/ubicaciones/${id}` : `{{ route('ubicaciones.store') }}`;

    if (id) formData.append('_method', 'PUT');

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(res => res.json())
    .then(() => {
        closeModalUbicacion();
        loadUbicaciones();
        Swal.fire({
            icon: 'success',
            title: id ? 'Ubicación actualizada' : 'Ubicación registrada',
            timer: 2000,
            showConfirmButton: false
        });
    });
});

document.addEventListener('DOMContentLoaded', loadUbicaciones);
</script>
@endsection
