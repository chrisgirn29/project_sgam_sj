@extends('layout.app')

@section('contents')
<style>
    #users-table th,
    #users-table td {
        text-align: center !important;
        vertical-align: middle;
    }
</style>

<div class="-mx-4 sm:-mx-6 lg:-mx-8 mt-6">

    <!-- Encabezado -->
    <div class="bg-white shadow p-4 flex justify-between items-center w-full">
        <h1 class="text-2xl font-semibold text-gray-700">
            Administración de Usuarios
        </h1>

        <button onclick="openModalUser()"
            class="bg-[#6fae2d] text-white px-4 py-2 text-sm font-semibold rounded-lg shadow-md flex items-center gap-2">
            <i class="fas fa-plus"></i> Agregar nuevo Usuario
        </button>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden w-full mt-4">
        <div class="px-4 py-2 font-semibold text-white  shadow-sm" style="background-color:#6fae2d;">USUARIOS</div>
        <div class="p-4">
            <table id="users-table" class="w-full text-left border-collapse table-fixed">
                <thead class="bg-gray-200 text-gray-700 text-sm uppercase text-center">

                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Nombre</th>
                        <th class="px-4 py-2">Correo</th>
                        <th class="px-4 py-2">Rol</th>
                        <th class="px-4 py-2">Estado</th>
                        <th class="px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody id="users-body" class="text-sm text-gray-700"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL -->
<div id="modalUser" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <div class="flex justify-between items-center border-b pb-2 mb-4">
            <h2 id="modalTitle" class="text-lg font-semibold text-gray-700">Registrar Usuario</h2>
            <button onclick="closeModalUser()" class="text-gray-500 hover:text-red-500 text-xl">&times;</button>
        </div>

        <form id="userForm" class="space-y-3">
            @csrf
            <input type="hidden" id="userId">

            <div>
                <label class="block text-sm font-medium text-gray-600">Nombre</label>
                <input id="name" required class="w-full border rounded px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Correo</label>
                <input id="email" type="email" required class="w-full border rounded px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Rol</label>
                <select id="rol_id" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">Seleccione un rol</option>
                </select>

            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Estado</label>
                <select id="estado" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Contraseña</label>
                <input id="password" type="password" class="w-full border rounded px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Confirmar contraseña</label>
                <input id="password_confirmation" type="password" class="w-full border rounded px-3 py-2 text-sm">
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <button type="button" onclick="closeModalUser()"
                        class="bg-gray-300 px-4 py-2 rounded">Cancelar</button>
                <button class="bg-[#6fae2d] text-white px-4 py-2 rounded">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- SCRIPTS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
let table;

// ===== DOM =====
const modalUser = document.getElementById('modalUser');
const modalTitle = document.getElementById('modalTitle');
const userForm = document.getElementById('userForm');

const userId = document.getElementById('userId');
const nameInput = document.getElementById('name');
const emailInput = document.getElementById('email');
const rolInput = document.getElementById('rol_id');
const estadoInput = document.getElementById('estado');
const passwordInput = document.getElementById('password');
const passwordConfirmInput = document.getElementById('password_confirmation');

const usersBody = document.getElementById('users-body');

// ===== MODAL =====
function openModalUser(edit = false, user = null) {
    userForm.reset();
    userId.value = '';
    modalTitle.innerText = edit ? 'Editar Usuario' : 'Registrar Usuario';

    if (edit && user) {
        userId.value = user.id;
        nameInput.value = user.name;
        emailInput.value = user.email;
        estadoInput.value = user.estado;
        loadRoles(user.rol_id);
    } else {
        loadRoles();
    }

    modalUser.classList.remove('hidden');
}

function closeModalUser() {
    modalUser.classList.add('hidden');
}

// ===== ROLES =====
function loadRoles(selectedRolId = null) {
    fetch("{{ route('roles.all') }}", {
        headers: { 'Accept': 'application/json' },
        credentials: 'same-origin'
    })
    .then(res => res.json())
    .then(data => {
        rolInput.innerHTML = '<option value="">Seleccione un rol</option>';

        data.forEach(r => {
            const option = document.createElement('option');
            option.value = r.id;     // id del rol
            option.textContent = r.rol; // nombre del rol
            if (selectedRolId == r.id) option.selected = true;
            rolInput.appendChild(option);
        });
    })
    .catch(() => {
        Swal.fire('Error', 'No se pudieron cargar los roles', 'error');
    });
}


// ===== USUARIOS =====
function loadUsers() {
    fetch("{{ route('users.all') }}", { credentials: 'same-origin' })
    .then(res => res.json())
    .then(data => {
        if (table) table.destroy();
        usersBody.innerHTML = '';

        data.forEach(u => {
            const estadoLabel = u.estado == 1
                ? `<span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">Activo</span>`
                : `<span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-semibold">Inactivo</span>`;

            usersBody.innerHTML += `
                <tr class="text-center">
                    <td>${u.id}</td>
                    <td>${u.name}</td>
                    <td>${u.email}</td>
                    <td>${u.rol_nombre}</td> <!-- 🔹 Asegúrate de usar rol_nombre -->
                    <td>${estadoLabel}</td>
                    <td>
                        <button class="text-orange-600 edit-btn" data-user='${JSON.stringify(u)}'>
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="ml-2 text-green-600 toggle-status-btn"
                            data-id="${u.id}" data-estado="${u.estado}">
                            <i class="fas fa-exchange-alt"></i>
                        </button>
                    </td>
                </tr>`;
        });

        table = $('#users-table').DataTable();
    });
}


// ===== EDITAR =====
document.addEventListener('click', e => {
    const btn = e.target.closest('.edit-btn');
    if (btn) {
        openModalUser(true, JSON.parse(btn.dataset.user));
    }
});
// ===== CAMBIO DE ESTADO CON CONFIRMACIÓN =====
document.addEventListener('click', e => {
    const btn = e.target.closest('.toggle-status-btn');
    if (!btn) return;

    const usuarioId = btn.dataset.id;
    const estadoActual = btn.dataset.estado;
    const nuevoEstado = estadoActual == 1 ? 0 : 1;
    const estadoTexto = nuevoEstado == 1 ? 'Activo' : 'Inactivo';

    // 🔹 Preguntar antes de cambiar
    Swal.fire({
        title: `¿Estás seguro de cambiar el estado de este usuario a "${estadoTexto}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, cambiar',
        cancelButtonText: 'No'
    }).then(result => {
        if (!result.isConfirmed) return;

        fetch(`/usuarios/${usuarioId}/estado`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ estado: nuevoEstado })
        })
        .then(res => res.json())
        .then(data => {
            // 🔹 Actualizamos la tabla
            loadUsers();

            // 🔹 Mensaje de éxito
            Swal.fire('Éxito', `El estado del usuario se cambió a "${estadoTexto}"`, 'success');
        })
        .catch(() => {
            Swal.fire('Error', 'No se pudo cambiar el estado del usuario', 'error');
        });
    });
});

// ===== ESTADO =====


// ===== GUARDAR (FIX REAL) =====
userForm.addEventListener('submit', e => {
    e.preventDefault();

    const id = userId.value;
    const url = id ? `/usuarios/${id}` : `/usuarios`;
    const method = id ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            name: nameInput.value,
            email: emailInput.value,
            rol_id: rolInput.value, // ✅ CORRECTO
            estado: estadoInput.value,
            password: passwordInput.value,
            password_confirmation: passwordConfirmInput.value
        })
    })
    .then(res => res.json())
    .then(() => {
        closeModalUser();
        loadUsers();
        Swal.fire('Éxito', 'Usuario guardado correctamente', 'success');
    })
    .catch(() => {
        Swal.fire('Error', 'No se pudo guardar el usuario', 'error');
    });
});


// ===== INIT =====
document.addEventListener('DOMContentLoaded', loadUsers);
</script>

@endsection
