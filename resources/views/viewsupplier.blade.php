@extends('layout.app')

@section('contents')
<style>
.section-title {
    font-size: 14px;
    font-weight: 600;
    color: #6fae2d;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.field label {
    font-size: 13px;
    color: #6b7280;
    display: block;
    margin-bottom: 4px;
}

.field input,
.field select {
    width: 100%;
    border: none;
    border-bottom: 2px solid #e5e7eb;
    padding: 6px 2px;
    font-size: 14px;
    background: transparent;
    transition: border-color 0.2s;
}

.field input:focus,
.field select:focus {
    outline: none;
    border-bottom-color: #6fae2d;
}
</style>

<div class="-mx-4 sm:-mx-6 lg:-mx-8 mt-6">

    <!-- Encabezado -->
    <div class="bg-white shadow p-4 flex justify-between items-center w-full">
        <div>
            <h1 class="text-2xl font-semibold text-gray-700">
                Administración de Proveedores.:
            </h1>
        </div>
        <button onclick="openModalCategoria()" class="bg-[#6fae2d] hover:bg-[#6fae2d] text-white px-4 py-2 text-sm font-semibold rounded-lg shadow-md flex items-center gap-2">
            <i class="fas fa-plus"></i> Agregar nuevo Proveedor
        </button>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden w-full mt-4">
        <div class="px-4 py-2 font-semibold text-white shadow-sm" style="background-color:#6fae2d;">
            PROVEEDORES
        </div>
        <div class="p-4">
           <table id="empleados-table" class="w-full">
                <thead class="bg-gray-200 text-sm">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>NIT</th>
                    <th>DPI</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="proveedores-table-body"></tbody>
            </table>

        </div>
    </div>
</div>

<!-- Modal CREAR / EDITAR -->
<div id="modalProveedor"
    class="fixed inset-0 bg-black/60 flex items-center justify-center hidden z-50">

    <div class="bg-white w-full max-w-3xl rounded-2xl shadow-2xl">

        <!-- HEADER -->
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h2 id="modalTitle" class="text-xl font-semibold text-gray-700">
                Registrar Proveedor
            </h2>
            <button onclick="closeModalProveedor()"
                class="text-2xl text-gray-400 hover:text-red-500 transition">
                &times;
            </button>
        </div>

        <!-- FORM -->
        <form id="formProveedor" class="p-6 space-y-6">
            @csrf
            <input type="hidden" id="proveedorId">
            <input type="hidden" name="usuario" value="{{ auth()->user()->name }}">
            <input type="hidden" name="estado" value="1">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Nombre -->
                <div class="relative">
                    <input type="text" name="nombre" required
                        class="peer w-full border border-gray-300 rounded-lg px-3 pt-5 pb-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#6fae2d] focus:border-[#6fae2d]" />
                    <label
                        class="absolute left-3 top-2 text-xs text-gray-500 peer-placeholder-shown:top-4 peer-placeholder-shown:text-sm peer-placeholder-shown:text-gray-400 transition-all">
                        Nombre del proveedor
                    </label>
                </div>

                <!-- NIT -->
                <div class="relative">
                    <input type="text" name="nit"
                        class="peer w-full border border-gray-300 rounded-lg px-3 pt-5 pb-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#6fae2d]" />
                    <label class="absolute left-3 top-2 text-xs text-gray-500">
                        NIT
                    </label>
                </div>

                <!-- DPI -->
                <div class="relative">
                    <input type="text" name="dpi"
                        class="peer w-full border border-gray-300 rounded-lg px-3 pt-5 pb-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#6fae2d]" />
                    <label class="absolute left-3 top-2 text-xs text-gray-500">
                        DPI
                    </label>
                </div>

                <!-- Teléfono -->
                <div class="relative">
                    <input type="text" name="telefono"
                        class="peer w-full border border-gray-300 rounded-lg px-3 pt-5 pb-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#6fae2d]" />
                    <label class="absolute left-3 top-2 text-xs text-gray-500">
                        Teléfono
                    </label>
                </div>

                <!-- Correo -->
                <div class="relative md:col-span-2">
                    <input type="email" name="correo"
                        class="peer w-full border border-gray-300 rounded-lg px-3 pt-5 pb-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#6fae2d]" />
                    <label class="absolute left-3 top-2 text-xs text-gray-500">
                        Correo electrónico
                    </label>
                </div>

                <!-- Dirección -->
                <div class="relative md:col-span-2">
                    <input type="text" name="direccion"
                        class="peer w-full border border-gray-300 rounded-lg px-3 pt-5 pb-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#6fae2d]" />
                    <label class="absolute left-3 top-2 text-xs text-gray-500">
                        Dirección
                    </label>
                </div>

            </div>

            <!-- FOOTER -->
            <div class="flex justify-end gap-3 pt-6 border-t">
                <button type="button" onclick="closeModalProveedor()"
                    class="px-5 py-2 rounded-lg border text-gray-600 hover:bg-gray-100 transition">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-6 py-2 rounded-lg bg-[#6fae2d] text-white font-semibold shadow hover:bg-[#5c9826] transition">
                    Guardar proveedor
                </button>
            </div>
        </form>

    </div>
</div>



<!-- Scripts -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>


<script>
let table;

/* =========================
   MODAL
========================= */
function openModalCategoria(edit = false, proveedor = null) {
    document.getElementById('formProveedor').reset();
    document.getElementById('proveedorId').value = '';

    document.getElementById('modalTitle').textContent = edit
        ? 'Editar Proveedor'
        : 'Registrar Proveedor';

    document.getElementById('modalProveedor').classList.remove('hidden');

    if (edit && proveedor) {
        document.getElementById('proveedorId').value = proveedor.id_proveedor;

        Object.keys(proveedor).forEach(k => {
            const input = document.querySelector(`[name="${k}"]`);
            if (input && proveedor[k] !== null) {
                input.value = proveedor[k];
            }
        });
    }
}

function closeModalProveedor() {
    document.getElementById('modalProveedor').classList.add('hidden');
}

/* =========================
   LISTAR PROVEEDORES
========================= */
function loadProveedores() {
    fetch("{{ route('proveedores.getAll') }}")
        .then(res => res.json())
        .then(data => {

            if (table) table.destroy();

            let tbody = document.getElementById('proveedores-table-body');
            tbody.innerHTML = '';

            data.forEach(p => {
                tbody.innerHTML += `
                <tr>
                    <td>${p.id_proveedor}</td>
                    <td>${p.nombre}</td>
                    <td>${p.nit ?? ''}</td>
                    <td>${p.dpi ?? ''}</td>
                    <td>${p.telefono ?? ''}</td>
                    <td>${p.correo ?? ''}</td>
                    <td>
                        ${p.estado == 1
                            ? '<span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">Activo</span>'
                            : '<span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs">Inactivo</span>'}
                    </td>
                    <td class="flex gap-2">
                        <button
                            onclick='openModalCategoria(true, ${JSON.stringify(p)})'
                            class="border border-orange-500 text-orange-500 hover:bg-orange-500 hover:text-white p-2 rounded-full"
                            title="Editar proveedor">
                            <i class="fas fa-pen"></i>
                        </button>

                        <button
                            onclick="toggleEstado(${p.id_proveedor})"
                            class="text-yellow-500 hover:text-indigo-600"
                            title="Cambiar estado">
                            <i class="fas fa-exchange-alt"></i>
                        </button>
                    </td>
                </tr>`;
            });

            table = $('#empleados-table').DataTable();
        });
}

/* =========================
   CAMBIAR ESTADO
========================= */
function toggleEstado(id) {
    Swal.fire({
        title: '¿Cambiar estado?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, cambiar'
    }).then(result => {
        if (result.isConfirmed) {
            fetch(`/proveedores/${id}/toggle-estado`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => {
                loadProveedores();
                Swal.fire('Listo', 'Estado actualizado', 'success');
            });
        }
    });
}

/* =========================
   GUARDAR / ACTUALIZAR
========================= */
document.getElementById('formProveedor').addEventListener('submit', e => {
    e.preventDefault();

    const formData = new FormData(e.target);
    const id = document.getElementById('proveedorId').value;

    let url = "{{ route('proveedores.store') }}";

    if (id) {
        url = `/proveedores/${id}`;
        formData.append('_method', 'PUT');
    }

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    }).then(() => {
        closeModalProveedor();
        loadProveedores();
        Swal.fire('Correcto', 'Proveedor guardado', 'success');
    });
});

/* =========================
   INIT
========================= */
document.addEventListener('DOMContentLoaded', loadProveedores);
</script>

@endsection
