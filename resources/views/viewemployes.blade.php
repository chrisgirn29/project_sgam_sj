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
                Administración de Empleados.:
            </h1>
        </div>
        <button onclick="openModalCategoria()" class="bg-[#6fae2d] hover:bg-[#6fae2d] text-white px-4 py-2 text-sm font-semibold rounded-lg shadow-md flex items-center gap-2">
            <i class="fas fa-plus"></i> Agregar nuevo Empleado
        </button>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden w-full mt-4">
        <div class="px-4 py-2 font-semibold text-white shadow-sm" style="background-color:#6fae2d;">
            CATEGORÍAS
        </div>
        <div class="p-4">
           <table id="empleados-table" class="w-full">
                <thead class="bg-gray-200 text-sm">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Puesto</th>
                        <th>Unidad</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="empleados-table-body"></tbody>
            </table>

        </div>
    </div>
</div>

<!-- Modal CREAR / EDITAR -->
<div id="modalCategoria" class="fixed inset-0 bg-black/40 flex items-center justify-center hidden z-50">
    <div class="bg-white w-full max-w-4xl rounded-xl shadow-xl overflow-hidden">

        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <div class="flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-[#6fae2d]"></span>
                <h2 id="modalTitle" class="text-lg font-semibold text-gray-800">
                    Registrar Empleado
                </h2>
            </div>
            <button onclick="closeModalCategoria()" class="text-gray-400 hover:text-red-500 text-xl">
                &times;
            </button>
        </div>

        <!-- Form -->
        <form id="formCategoria" class="p-6 space-y-6">

            <input type="hidden" id="empleadoId">
            @csrf
            <input type="hidden" name="usuario" value="{{ auth()->user()->name }}">
            <input type="hidden" name="estado" id="estado" value="1">

            <!-- Información Personal -->
            <div>
                <h3 class="section-title">Información personal.:</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-4">
                    <div class="field">
                        <label>Nombre completo</label>
                        <input type="text" name="nombre_completo" required>
                    </div>

                    <div class="field">
                        <label>Correo</label>
                        <input type="email" name="correo" required>
                    </div>

                    <div class="field">
                        <label>Teléfono</label>
                        <input type="text" name="telefono">
                    </div>

                    <div class="field">
                        <label>DPI</label>
                        <input type="text" name="dpi">
                    </div>

                    <div class="field">
                        <label>Sexo</label>
                        <select name="sexo">
                            <option value="">Seleccione</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>

                    <div class="field">
                        <label>Edad</label>
                        <input type="number" name="edad">
                    </div>
                    <div class="field md:col-span-3">
                        <label>Dirección</label>
                        <input type="text" name="direccion">
                    </div>
                </div>
            </div>


            <!-- Información Laboral -->
            <div>
                <h3 class="section-title">Información laboral.:</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-4">
                    <div class="field">
                        <label>Puesto</label>
                        <input type="text" name="puesto">
                    </div>

                    <div class="field">
                        <label>Unidad</label>
                        <input type="text" name="unidad">
                    </div>

                    <div class="field">
                        <label>Tipo</label>
                        <input type="text" name="tipo">
                    </div>

                    <div class="field">
                        <label>Responsable</label>
                        <select name="responsable" id="responsableSelect">
                            <option value="">Seleccione un responsable</option>
                        </select>
                    </div>

                    <div class="field">
                        <label>Director</label>
                        <select name="director" id="directorSelect">
                            <option value="">Seleccione un director</option>
                        </select>
                    </div>


                    <div class="field">
                        <label>Renglón</label>
                        <input type="number" name="renglon">
                    </div>


                </div>
            </div>

            <!-- Acciones -->
            <div class="flex justify-end gap-3 pt-4 border-t">
                <button type="button"
                    onclick="closeModalCategoria()"
                    class="px-4 py-2 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-100">
                    Cancelar
                </button>

                <button type="submit"
                    class="px-6 py-2 rounded-md bg-[#6fae2d] hover:bg-[#5c9726] text-white font-medium">
                    Guardar cambios
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
function openModalCategoria(edit = false, empleado = null) {
    document.getElementById('formCategoria').reset();
    document.getElementById('empleadoId').value = '';

    document.getElementById('modalTitle').textContent = edit
        ? 'Editar Empleado'
        : 'Registrar Empleado';

    document.getElementById('modalCategoria').classList.remove('hidden');

    if (edit && empleado) {
        cargarEmpleadosSelect(empleado).then(() => {
            cargarDatosEmpleadoBasicos(empleado);
        });
    } else {
        cargarEmpleadosSelect();
    }
}

function closeModalCategoria() {
    document.getElementById('modalCategoria').classList.add('hidden');
}

/* =========================
   CARGAR DATOS EMPLEADO
========================= */
function cargarDatosEmpleadoBasicos(e) {
    document.getElementById('empleadoId').value = e.id_empleado;

    Object.keys(e).forEach(k => {
        if (['responsable', 'director'].includes(k)) return;

        const input = document.querySelector(`[name="${k}"]`);
        if (input && e[k] !== null) {
            input.value = e[k];
        }
    });

    document.querySelector('[name="sexo"]').value = e.sexo ?? '';
}

/* =========================
   EDITAR
========================= */
function editEmpleado(empleado) {
    openModalCategoria(true, empleado);
}

/* =========================
   SELECT RESPONSABLE / DIRECTOR
========================= */
function cargarEmpleadosSelect(empleado = null) {
    return fetch("{{ route('empleados.getAll') }}")
        .then(res => res.json())
        .then(data => {
            const responsable = document.getElementById('responsableSelect');
            const director = document.getElementById('directorSelect');

            responsable.innerHTML = '<option value="">Seleccione un responsable</option>';
            director.innerHTML = '<option value="">Seleccione un director</option>';

            data.forEach(emp => {
                const optResp = document.createElement('option');
                optResp.value = emp.id_empleado;
                optResp.textContent = emp.nombre_completo;

                const optDir = document.createElement('option');
                optDir.value = emp.id_empleado;
                optDir.textContent = emp.nombre_completo;

                responsable.appendChild(optResp);
                director.appendChild(optDir);
            });

            // ✅ ASIGNAR DESPUÉS DE CARGAR OPCIONES
            if (empleado) {
                responsable.value = empleado.responsable ?? '';
                director.value = empleado.director ?? '';
            }
        });
}

/* =========================
   LISTAR EMPLEADOS
========================= */
function loadEmpleados() {
    fetch("{{ route('empleados.getAll') }}")
        .then(res => res.json())
        .then(data => {
            if (table) table.destroy();

            let tbody = document.getElementById('empleados-table-body');
            tbody.innerHTML = '';

            data.forEach(e => {
                tbody.innerHTML += `
                <tr>
                    <td>${e.id_empleado}</td>
                    <td>${e.nombre_completo}</td>
                    <td>${e.puesto ?? ''}</td>
                    <td>${e.unidad ?? ''}</td>
                    <td>${e.telefono ?? ''}</td>
                    <td>${e.correo ?? ''}</td>
                    <td>
                        ${e.estado === 'activo'
                            ? '<span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">Activo</span>'
                            : '<span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs">Inactivo</span>'}
                    </td>
                    <td class="flex gap-2">
                        <button
                            onclick='editEmpleado(${JSON.stringify(e)})'
                            class="border border-orange-500 text-orange-500 hover:bg-orange-500 hover:text-white p-2 rounded-full transition"
                            title="Editar empleado">
                            <i class="fas fa-pen"></i>
                        </button>

                        <button
                            onclick="toggleEstado(${e.id_empleado})"
                            class="text-yellow-400 hover:text-indigo-600 transition"
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
        text: 'Este cambio se puede revertir',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, cambiar',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {
            fetch(`/empleados/${id}/toggle-estado`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(() => {
                loadEmpleados();
                Swal.fire({
                    icon: 'success',
                    title: 'Estado actualizado',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
        }
    });
}

/* =========================
   GUARDAR / ACTUALIZAR
========================= */
document.getElementById('formCategoria').addEventListener('submit', e => {
    e.preventDefault();

    const formData = new FormData(e.target);
    const id = document.getElementById('empleadoId').value;

    let url = "{{ route('empleados.store') }}";
    if (id) {
        url = `/empleados/${id}`;
        formData.append('_method', 'PUT');
    }

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    }).then(() => {
        closeModalCategoria();
        loadEmpleados();
        Swal.fire('Correcto', 'Registro guardado', 'success');
    });
});

/* =========================
   INIT
========================= */
document.addEventListener('DOMContentLoaded', loadEmpleados);
</script>


@endsection
