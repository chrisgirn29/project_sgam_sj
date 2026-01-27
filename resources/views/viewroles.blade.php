@extends('layout.app')

@section('contents')

<style>
    #roles-table th,
    #roles-table td {
        text-align: center !important;
        vertical-align: middle;
    }
</style>

<div class="-mx-4 sm:-mx-6 lg:-mx-8 mt-6">

    <!-- ENCABEZADO -->
    <div class="bg-white shadow p-4 flex justify-between items-center w-full">
        <h1 class="text-2xl font-semibold text-gray-700">
            Administración de Roles
        </h1>

        <button onclick="openModalRol()"
            class="bg-[#6fae2d] text-white px-4 py-2 text-sm font-semibold rounded-lg shadow-md flex items-center gap-2">
            <i class="fas fa-plus"></i> Agregar Rol
        </button>
    </div>

    <!-- TABLA -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden w-full mt-4">
        <div class="px-4 py-2 font-semibold text-white shadow-sm" style="background-color:#6fae2d;">
            ROLES
        </div>

        <div class="p-4">
            <table id="roles-table" class="w-full border-collapse table-fixed">
                <thead class="bg-gray-200 text-gray-700 text-sm uppercase">
                    <tr>
                        <th>ID</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="roles-body" class="text-sm text-gray-700"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL -->
<div id="modalRol" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">

        <div class="flex justify-between items-center border-b pb-2 mb-4">
            <h2 id="modalTitle" class="text-lg font-semibold text-gray-700">
                Registrar Rol
            </h2>
            <button onclick="closeModalRol()" class="text-gray-500 hover:text-red-500 text-xl">&times;</button>
        </div>

        <form id="rolForm" class="space-y-4">
            @csrf
            <input type="hidden" id="rolId">

            <div>
                <label class="block text-sm font-medium text-gray-600">Nombre del Rol</label>
                <input id="rolNombre" required class="w-full border rounded px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Estado</label>
                <select id="estado" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <button type="button" onclick="closeModalRol()"
                    class="bg-gray-300 px-4 py-2 rounded">
                    Cancelar
                </button>
                <button class="bg-[#6fae2d] text-white px-4 py-2 rounded">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- SCRIPTS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
let table;

const modalRol = document.getElementById('modalRol');
const modalTitle = document.getElementById('modalTitle');
const rolForm = document.getElementById('rolForm');

const rolId = document.getElementById('rolId');
const rolNombre = document.getElementById('rolNombre');
const estadoInput = document.getElementById('estado');
const rolesBody = document.getElementById('roles-body');

// ===== MODAL =====
function openModalRol(edit = false, rol = null) {
    rolForm.reset();
    rolId.value = '';
    modalTitle.innerText = edit ? 'Editar Rol' : 'Registrar Rol';

    if (edit && rol) {
        rolId.value = rol.id;
        rolNombre.value = rol.rol;
        estadoInput.value = rol.estado;
    }

    modalRol.classList.remove('hidden');
}

function closeModalRol() {
    modalRol.classList.add('hidden');
}

// ===== CARGAR ROLES =====
function loadRoles() {
    fetch("{{ route('roles.all') }}")
        .then(res => res.json())
        .then(data => {

            if (table) {
                table.destroy();
                table = null;
            }

            rolesBody.innerHTML = '';

            data.forEach(r => {

                const estadoLabel = r.estado == 1
                    ? `<span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">Activo</span>`
                    : `<span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-semibold">Inactivo</span>`;

                rolesBody.innerHTML += `
                    <tr>
                        <td>${r.id}</td>
                        <td>${r.rol}</td>
                        <td>${estadoLabel}</td>
                        <td>
                            <button class="edit-btn text-orange-600 hover:text-orange-800"
                                data-rol='${JSON.stringify(r)}'>
                                <i class="fas fa-edit"></i>
                            </button>

                            <button class="ml-2 toggle-btn text-green-600 hover:text-green-800"
                                data-id="${r.id}"
                                data-estado="${r.estado}">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });

            table = $('#roles-table').DataTable();
        });
}

// ===== EVENTOS =====
document.addEventListener('click', e => {

    const editBtn = e.target.closest('.edit-btn');
    if (editBtn) {
        openModalRol(true, JSON.parse(editBtn.dataset.rol));
    }

    const toggleBtn = e.target.closest('.toggle-btn');
    if (toggleBtn) {

        const id = toggleBtn.dataset.id;
        const nuevoEstado = toggleBtn.dataset.estado == 1 ? 0 : 1;

        Swal.fire({
            title: '¿Cambiar estado?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#6fae2d',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, cambiar'
        }).then(result => {
            if (!result.isConfirmed) return;

            fetch(`/roles/${id}/estado`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ estado: nuevoEstado })
            })
            .then(() => {
                loadRoles();
                Swal.fire('Actualizado', 'Estado modificado correctamente', 'success');
            });
        });
    }
});

// ===== GUARDAR / ACTUALIZAR =====
rolForm.addEventListener('submit', e => {
    e.preventDefault();

    const id = rolId.value;
    const url = id ? `/roles/${id}` : `/roles`;

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            rol: rolNombre.value,
            estado: estadoInput.value,
            _method: id ? 'PUT' : 'POST'
        })
    })
    .then(() => {
        closeModalRol();
        loadRoles();

        Swal.fire({
            icon: 'success',
            title: id ? 'Rol actualizado' : 'Rol creado',
            timer: 1800,
            showConfirmButton: false
        });
    });
});

// ===== INIT =====
document.addEventListener('DOMContentLoaded', loadRoles);
</script>

@endsection
