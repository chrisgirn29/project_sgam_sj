@extends('layout.app')

@section('contents')
<div class="-mx-4 sm:-mx-6 lg:-mx-8 mt-6">

    <!-- Encabezado -->
    <div class="bg-white shadow p-4 flex justify-between items-center w-full">
        <div>
            <h1 class="text-2xl font-semibold text-gray-700">
                Administración de Categorías
            </h1>
        </div>
        <button onclick="openModalCategoria()"
            class="bg-[#6fae2d] hover:bg-[#6fae2d] text-white px-4 py-2 text-sm font-semibold rounded-lg shadow-md flex items-center gap-2">
            <i class="fas fa-plus"></i>
            Agregar nueva Categoría
        </button>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden w-full mt-4">
        <div class="px-4 py-2 font-semibold text-white  shadow-sm" style="background-color:#6fae2d;">CATEGORÍAS</div>
        <div class="p-4">
            <table id="categorias-table" class="w-full text-left border-collapse">
                <thead class="bg-gray-200 text-gray-700 text-sm uppercase">
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Descripción</th>
                        <th class="px-4 py-2">Estado</th>
                        <th class="px-4 py-2">Usuario</th>
                        <th class="px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody id="categorias-table-body" class="text-sm text-gray-700"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal CREAR/EDITAR CATEGORIA -->
<div id="modalCategoria" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg w-96 p-6">
        <div class="flex justify-between items-center border-b pb-2 mb-4">
            <h2 id="modalTitle" class="text-lg font-semibold text-gray-700">Registrar Categoría</h2>
            <button onclick="closeModalCategoria()" class="text-gray-500 hover:text-red-500 text-xl">&times;</button>
        </div>
        <form id="formCategoria" class="space-y-4">
            @csrf
            <input type="hidden" name="id_categoria" id="categoriaId">

            <div>
                <label class="block text-sm font-medium text-gray-600">Descripción</label>
                <input type="text" name="descripcion" id="descripcion" required
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>

            <input type="hidden" name="usuario" value="{{ auth()->user()->name }}">
            <input type="hidden" name="estado" id="estado" value="1">

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModalCategoria()"
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
let categoriasTable;

function openModalCategoria(edit = false, categoria = null) {
    document.getElementById('formCategoria').reset();
    document.getElementById('categoriaId').value = '';
    document.getElementById('modalTitle').textContent = edit ? 'Editar Categoría' : 'Registrar Categoría';

    if (edit && categoria) {
        document.getElementById('categoriaId').value = categoria.id_categoria;
        document.getElementById('descripcion').value = categoria.descripcion;
        document.getElementById('estado').value = categoria.estado;
    }
    document.getElementById('modalCategoria').classList.remove('hidden');
}

function closeModalCategoria() {
    document.getElementById('modalCategoria').classList.add('hidden');
}

function loadCategorias() {
    fetch("{{ route('categorias.getAll') }}")
        .then(res => res.json())
        .then(data => {
            if (categoriasTable) categoriasTable.clear().destroy();
            const tbody = document.getElementById('categorias-table-body');
            tbody.innerHTML = '';
            data.forEach(c => {
                const estadoLabel = c.estado == 1
                    ? `<span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Activo</span>`
                    : `<span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Inactivo</span>`;

                const acciones = `
                    <div class="flex gap-2">
                        <button class="text-orange-600 hover:text-yellow-800 btn-edit"
                                data-categoria='${JSON.stringify(c)}' title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="text-yellow-600 hover:text-orange-800 btn-toggle"
                                data-id="${c.id_categoria}" title="Cambiar estado">
                            <i class="fas fa-exchange-alt"></i>
                        </button>
                    </div>
                `;

                tbody.innerHTML += `
                    <tr>
                        <td>${c.id_categoria}</td>
                        <td>${c.descripcion}</td>
                        <td>${estadoLabel}</td>
                        <td>${c.usuario}</td>
                        <td>${acciones}</td>
                    </tr>`;
            });
            categoriasTable = $('#categorias-table').DataTable();
        });
}

document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-edit')) {
        const categoria = JSON.parse(e.target.closest('.btn-edit').dataset.categoria);
        openModalCategoria(true, categoria);
    }

    if (e.target.closest('.btn-toggle')) {
        const id = e.target.closest('.btn-toggle').dataset.id;
        fetch(`/categorias/toggle-estado/${id}`, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            loadCategorias();
            Swal.fire({
                icon: 'success',
                title: 'Estado actualizado',
                text: `El estado ahora es: ${data.estado == 1 ? 'Activo' : 'Inactivo'}`,
                timer: 1500,
                showConfirmButton: false
            });
        });
    }
});

document.getElementById('formCategoria').addEventListener('submit', e => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const id = document.getElementById('categoriaId').value;
    const url = id ? `/categorias/${id}` : `{{ route('categorias.store') }}`;
    const method = id ? 'POST' : 'POST';

    if (id) formData.append('_method', 'PUT');

    fetch(url, {
        method: method,
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        closeModalCategoria();
        loadCategorias();
        Swal.fire({
            icon: 'success',
            title: id ? 'Categoría actualizada' : 'Categoría registrada',
            timer: 2000,
            showConfirmButton: false
        });
    });
});

document.addEventListener('DOMContentLoaded', loadCategorias);
</script>
@endsection
