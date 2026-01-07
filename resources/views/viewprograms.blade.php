@extends('layout.app')

@section('contents')
<div class="-mx-4 sm:-mx-6 lg:-mx-8 mt-6">

    <!-- Encabezado -->
    <div class="bg-white shadow p-4 flex justify-between items-center w-full">
        <h1 class="text-2xl font-semibold text-gray-700">
            Administración de Programas
        </h1>

        <button onclick="openModalCategoria()"
            class="bg-[#6fae2d] text-white px-4 py-2 text-sm font-semibold rounded-lg shadow-md flex items-center gap-2">
            <i class="fas fa-plus"></i>
            Agregar Nuevo Programa
        </button>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden w-full mt-4">
        <div class="px-4 py-2 font-semibold text-white shadow-sm" style="background-color:#6fae2d;">
            PROGRAMAS
        </div>

        <div class="p-4">
            <table id="categorias-table" class="w-full text-left border-collapse table-fixed">
                <thead class="bg-gray-200 text-gray-700 text-sm uppercase">
                    <tr>
                        <th class="px-4 py-2 w-[5%]">ID</th>
                        <th class="px-4 py-2 w-[50%]">Nombre</th>
                        <th class="px-4 py-2 w-[15%]">Tipo</th>
                        <th class="px-4 py-2 w-[10%]">Estado</th>
                        <th class="px-4 py-2 w-[10%]">Usuario</th>
                        <th class="px-4 py-2 w-[10%]">Acciones</th>
                    </tr>
                </thead>
                <tbody id="categorias-table-body" class="text-sm text-gray-700"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL CREAR / EDITAR PROGRAMA -->
<div id="modalCategoria" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg w-96 p-6">
        <div class="flex justify-between items-center border-b pb-2 mb-4">
            <h2 id="modalTitle" class="text-lg font-semibold text-gray-700">
                Registrar Programa
            </h2>
            <button onclick="closeModalCategoria()" class="text-gray-500 hover:text-red-500 text-xl">&times;</button>
        </div>

        <form id="formCategoria" class="space-y-4">
            @csrf
            <input type="hidden" name="id_programa" id="programaId">

            <!-- Nombre -->
            <div>
                <label class="block text-sm font-medium text-gray-600">Nombre del Programa</label>
                <textarea name="nombre" id="nombre" required rows="6"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm resize-y"
                    placeholder="Ingrese el nombre o descripción del programa"></textarea>
            </div>

            <!-- Tipo -->
            <div>
                <label class="block text-sm font-medium text-gray-600">Tipo</label>
                <select name="tipo" id="tipo" required
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="">Seleccione un tipo</option>
                    <option value="funcionamiento">Funcionamiento</option>
                    <option value="inversion">Inversión</option>
                </select>
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
let filtroAnio = '';

/* FILTRO PERSONALIZADO POR AÑO */
$.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
    if (!filtroAnio) return true;

    const row = categoriasTable.row(dataIndex).data();
    const created = row.created_at ? new Date(row.created_at).getFullYear() : null;
    const updated = row.updated_at ? new Date(row.updated_at).getFullYear() : null;

    return created == filtroAnio || updated == filtroAnio;
});

/* Abrir modal */
function openModalCategoria(edit = false, programa = null) {
    document.getElementById('formCategoria').reset();
    document.getElementById('programaId').value = '';
    document.getElementById('modalTitle').textContent =
        edit ? 'Editar Programa' : 'Registrar Programa';

    if (edit && programa) {
        document.getElementById('programaId').value = programa.id_programa;
        document.getElementById('nombre').value = programa.nombre;
        document.getElementById('tipo').value = programa.tipo;
        document.getElementById('estado').value = programa.estado;
    }

    document.getElementById('modalCategoria').classList.remove('hidden');
}

function closeModalCategoria() {
    document.getElementById('modalCategoria').classList.add('hidden');
}

/* Cargar programas */
function loadCategorias() {
    fetch("{{ route('programas.getAll') }}")
        .then(res => res.json())
        .then(data => {
            if (categoriasTable) categoriasTable.clear().destroy();

            const tbody = document.getElementById('categorias-table-body');
            tbody.innerHTML = '';

            data.forEach(p => {
                const estadoLabel = p.estado == 1
                    ? `<span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Activo</span>`
                    : `<span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Inactivo</span>`;

                tbody.innerHTML += `
                    <tr>
                        <td class="px-4 py-2">${p.id_programa}</td>
                        <td class="px-4 py-2 break-words">${p.nombre}</td>
                        <td class="px-4 py-2 capitalize">${p.tipo}</td>
                        <td class="px-4 py-2">${estadoLabel}</td>
                        <td class="px-4 py-2">${p.usuario}</td>
                        <td class="px-4 py-2 flex gap-3">
                            <button class="text-orange-600 btn-edit" data-categoria='${JSON.stringify(p)}'>
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-toggle ${p.estado == 1 ? 'text-green-600' : 'text-red-600'}"
                                data-id="${p.id_programa}">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                        </td>
                    </tr>`;
            });

            categoriasTable = $('#categorias-table').DataTable({
                dom: '<"flex justify-between items-center mb-2"l<"flex items-center gap-2"f<"filtro-anio">>>rtip'
            });

            /* UI FILTRO */
            let options = `<option value="">Todos</option>`;
            const year = new Date().getFullYear();
            for (let i = year; i >= year - 10; i--) {
                options += `<option value="${i}">${i}</option>`;
            }

            $('.filtro-anio').html(`
                <select id="selectAnio" class="border rounded px-2 py-1 text-sm">
                    ${options}
                </select>
                <button id="btnMostrar" class="bg-[#6fae2d] text-white px-3 py-1 rounded text-sm">
                    Mostrar
                </button>
            `);

            $('#btnMostrar').on('click', () => {
                filtroAnio = $('#selectAnio').val();
                categoriasTable.draw();
            });
        });
}

/* Eventos */
document.addEventListener('click', e => {

    if (e.target.closest('.btn-edit')) {
        const programa = JSON.parse(e.target.closest('.btn-edit').dataset.categoria);
        openModalCategoria(true, programa);
    }

    if (e.target.closest('.btn-toggle')) {
        const id = e.target.closest('.btn-toggle').dataset.id;

        fetch(`/programas/toggle-estado/${id}`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(() => {
            loadCategorias();
            Swal.fire({
                icon: 'success',
                title: 'Estado actualizado',
                timer: 1500,
                showConfirmButton: false
            });
        });
    }
});

/* Guardar / actualizar */
document.getElementById('formCategoria').addEventListener('submit', e => {
    e.preventDefault();

    const formData = new FormData(e.target);
    const id = document.getElementById('programaId').value;

    const url = id ? `/programas/${id}` : `{{ route('programas.store') }}`;
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
        closeModalCategoria();
        loadCategorias();
        Swal.fire({
            icon: 'success',
            title: id ? 'Programa actualizado' : 'Programa registrado',
            timer: 1500,
            showConfirmButton: false
        });
    });
});

document.addEventListener('DOMContentLoaded', loadCategorias);
</script>
@endsection
