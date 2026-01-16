@extends('layout.app')

@section('contents')
<div class="-mx-4 sm:-mx-6 lg:-mx-8 mt-6">

    <!-- Encabezado -->
    <div class="bg-white shadow p-4 flex justify-between items-center w-full">
        <h1 class="text-2xl font-semibold text-gray-700">
            Administración de Renglones
        </h1>

        <button onclick="openModalCategoria()"
            class="bg-[#6fae2d] text-white px-4 py-2 text-sm font-semibold rounded-lg shadow-md flex items-center gap-2">
            <i class="fas fa-plus"></i>
            Agregar Nuevo Renglón
        </button>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden w-full mt-4">
        <div class="px-4 py-2 font-semibold text-white shadow-sm" style="background-color:#6fae2d;">
            RENGLONES
        </div>

        <div class="p-4">
            <table id="categorias-table" class="w-full text-left border-collapse table-fixed">
                <thead class="bg-gray-200 text-gray-700 text-sm uppercase">
                    <tr>
                        <th class="px-4 py-2 w-[5%]">ID</th>
                        <th class="px-4 py-2 w-[40%]">Nombre</th>
                        <th class="px-4 py-2 w-[10%]">Renglón</th>
                        <th class="px-4 py-2 w-[10%]">Grupo</th>
                        <th class="px-4 py-2 w-[10%]">Estado</th>
                        <th class="px-4 py-2 w-[10%]">Usuario</th>
                        <th class="px-4 py-2 w-[15%]">Acciones</th>
                    </tr>
                </thead>
                <tbody id="categorias-table-body" class="text-sm text-gray-700"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL CREAR / EDITAR RENGLÓN -->
<div id="modalCategoria" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg w-96 p-6">
        <div class="flex justify-between items-center border-b pb-2 mb-4">
            <h2 id="modalTitle" class="text-lg font-semibold text-gray-700">
                Registrar Renglón
            </h2>
            <button onclick="closeModalCategoria()" class="text-gray-500 hover:text-red-500 text-xl">&times;</button>
        </div>

        <form id="formCategoria" class="space-y-4">
            @csrf
            <input type="hidden" name="id_renglon" id="renglonId">

            <!-- Renglón -->
            <div>
                <label class="block text-sm font-medium text-gray-600">Renglón</label>
                <input type="number" name="renglon" id="renglon" required
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm"
                    placeholder="Ej: 011">
            </div>

            <!-- Nombre -->
            <div>
                <label class="block text-sm font-medium text-gray-600">Nombre</label>
                <textarea name="nombre" id="nombre" required rows="4"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm resize-y"
                    placeholder="Descripción del renglón"></textarea>
            </div>

            <!-- Grupo -->
            <div>
                <label class="block text-sm font-medium text-gray-600">Grupo</label>
                <input type="number" name="grupo" id="grupo" required
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm"
                    placeholder="Ej: 1">
            </div>

            <!-- Estado -->
            <div>
                <label class="block text-sm font-medium text-gray-600">Estado</label>
                <select name="estado" id="estado"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>

            <input type="hidden" name="usuario" value="{{ auth()->user()->name }}">

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModalCategoria()"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                    Cancelar
                </button>
                <button type="submit"
                    class="bg-[#6fae2d] text-white px-4 py-2 rounded">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- LIBRERÍAS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css"/>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
let categoriasTable;

/* MODAL */
function openModalCategoria(edit = false, renglon = null) {
    document.getElementById('formCategoria').reset();
    document.getElementById('renglonId').value = '';
    document.getElementById('modalTitle').textContent =
        edit ? 'Editar Renglón' : 'Registrar Renglón';

    if (edit && renglon) {
        document.getElementById('renglonId').value = renglon.id_renglon;
        document.getElementById('renglon').value = renglon.renglon;
        document.getElementById('nombre').value = renglon.nombre;
        document.getElementById('grupo').value = renglon.grupo;
        document.getElementById('estado').value = renglon.estado;
    }

    document.getElementById('modalCategoria').classList.remove('hidden');
}

function closeModalCategoria() {
    document.getElementById('modalCategoria').classList.add('hidden');
}

/* CARGAR RENGLONES */
function loadCategorias() {
    fetch("{{ route('renglones.getAll') }}")
        .then(res => res.json())
        .then(data => {

            if (categoriasTable) categoriasTable.clear().destroy();

            const tbody = document.getElementById('categorias-table-body');
            tbody.innerHTML = '';

            data.forEach(r => {
                const estadoLabel = r.estado == 1
                    ? `<span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Activo</span>`
                    : `<span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Inactivo</span>`;

                tbody.innerHTML += `
                    <tr>
                        <td>${r.id_renglon}</td>
                        <td class="break-words">${r.nombre}</td>
                        <td>${r.renglon}</td>
                        <td>${r.grupo}</td>
                        <td>${estadoLabel}</td>
                        <td>${r.usuario}</td>
                        <td class="flex gap-3">
                            <button class="text-orange-600 btn-edit" data-renglon='${JSON.stringify(r)}'>
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-toggle ${r.estado == 1 ? 'text-green-600' : 'text-red-600'}"
                                data-id="${r.id_renglon}">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                        </td>
                    </tr>`;
            });

            categoriasTable = $('#categorias-table').DataTable();
        });
}

/* EVENTOS */
document.addEventListener('click', e => {

    if (e.target.closest('.btn-edit')) {
        const renglon = JSON.parse(e.target.closest('.btn-edit').dataset.renglon);
        openModalCategoria(true, renglon);
    }

    if (e.target.closest('.btn-toggle')) {
        const id = e.target.closest('.btn-toggle').dataset.id;

        fetch(`/renglones/toggle-estado/${id}`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(() => loadCategorias());
    }
});

/* GUARDAR / ACTUALIZAR */
document.getElementById('formCategoria').addEventListener('submit', e => {
    e.preventDefault();

    const formData = new FormData(e.target);
    const id = document.getElementById('renglonId').value;

    const url = id ? `/renglones/${id}` : `{{ route('renglones.store') }}`;
    if (id) formData.append('_method', 'PUT');

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(() => {
        closeModalCategoria();
        loadCategorias();
        Swal.fire({
            icon: 'success',
            title: id ? 'Renglón actualizado' : 'Renglón registrado',
            timer: 1500,
            showConfirmButton: false
        });
    });
});

document.addEventListener('DOMContentLoaded', loadCategorias);
</script>
@endsection
