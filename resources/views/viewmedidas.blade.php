@extends('layout.app')

@section('contents')
<div class="-mx-4 sm:-mx-6 lg:-mx-8 mt-6">

    <!-- Encabezado -->
    <div class="bg-white shadow p-4 flex justify-between items-center w-full">
        <div>
            <h1 class="text-2xl font-semibold text-gray-700">
                Administración de Medidas.:
            </h1>
        </div>
        <button onclick="openModalMedida()"
            class="bg-[#6fae2d] hover:bg-[#6fae2d] text-white px-4 py-2 text-sm font-semibold rounded-lg shadow-md flex items-center gap-2">
            <i class="fas fa-plus"></i>
            Agregar nueva Medida
        </button>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden w-fullf mt-4">

        <div class="px-4 py-2 font-semibold text-white  shadow-sm" style="background-color:#6fae2d;">MEDIDAS</div>

        <div class="p-4">
            <table id="medidas-table" class="w-full text-left border-collapse">
                <thead class="bg-gray-200 text-gray-700 text-sm uppercase">
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Descripción</th>
                        <th class="px-4 py-2">Estado</th>
                        <th class="px-4 py-2">Usuario</th>
                        <th class="px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody id="medidas-table-body" class="text-sm text-gray-700"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal CREAR/EDITAR MEDIDAS -->
<div id="modalMedida" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg w-96 p-6">
        <div class="flex justify-between items-center border-b pb-2 mb-4">
            <h2 id="modalTitle" class="text-lg font-semibold text-gray-700">Registrar Medida</h2>
            <button onclick="closeModalMedida()" class="text-gray-500 hover:text-red-500 text-xl">&times;</button>
        </div>
        <form id="formMedida" class="space-y-4">
            @csrf
            <input type="hidden" name="id_medida" id="medidaId">

            <div>
                <label class="block text-sm font-medium text-gray-600">Descripción</label>
                <input type="text" name="descripcion" id="descripcion" required
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>

            <input type="hidden" name="usuario" value="{{ auth()->user()->name }}">
            <input type="hidden" name="estado" id="estado" value="1">

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModalMedida()"
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
let medidasTable;

function openModalMedida(edit = false, medida = null) {
    document.getElementById('formMedida').reset();
    document.getElementById('medidaId').value = '';
    document.getElementById('modalTitle').textContent = edit ? 'Editar Medida' : 'Registrar Medida';

    if (edit && medida) {
        document.getElementById('medidaId').value = medida.id_medida;
        document.getElementById('descripcion').value = medida.descripcion;
        document.getElementById('estado').value = medida.estado;
    }
    document.getElementById('modalMedida').classList.remove('hidden');
}

function closeModalMedida() {
    document.getElementById('modalMedida').classList.add('hidden');
}

function loadMedidas() {
    fetch("{{ route('medidas.getAll') }}")
        .then(res => res.json())
        .then(data => {
            if (medidasTable) medidasTable.clear().destroy();
            const tbody = document.getElementById('medidas-table-body');
            tbody.innerHTML = '';
            data.forEach(c => {
                const estadoLabel = c.estado == 1
                    ? `<span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Activo</span>`
                    : `<span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Inactivo</span>`;

                const acciones = `
                    <div class="flex gap-2">
                        <button class="text-orange-600 hover:text-yellow-800 btn-edit"
                                data-medida='${JSON.stringify(c)}' title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="text-yellow-600 hover:text-orange-800 btn-toggle"
                                data-id="${c.id_medida}" title="Cambiar estado">
                            <i class="fas fa-exchange-alt"></i>
                        </button>
                    </div>
                `;

                tbody.innerHTML += `
                    <tr>
                        <td>${c.id_medida}</td>
                        <td>${c.descripcion}</td>
                        <td>${estadoLabel}</td>
                        <td>${c.usuario}</td>
                        <td>${acciones}</td>
                    </tr>`;
            });
            medidasTable = $('#medidas-table').DataTable();
        });
}

document.addEventListener('click', function(e) {
    if (e.target.closest('.btn-edit')) {
        const medida = JSON.parse(e.target.closest('.btn-edit').dataset.medida);
        openModalMedida(true, medida);
    }

    if (e.target.closest('.btn-toggle')) {
        const id = e.target.closest('.btn-toggle').dataset.id;
        fetch(`/medidas/toggle-estado/${id}`, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            loadMedidas();
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

document.getElementById('formMedida').addEventListener('submit', e => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const id = document.getElementById('medidaId').value;
    const url = id ? `/medidas/${id}` : `{{ route('medidas.store') }}`;
    const method = id ? 'POST' : 'POST';

    if (id) formData.append('_method', 'PUT');

    fetch(url, {
        method: method,
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        closeModalMedida();
        loadMedidas();
        Swal.fire({
            icon: 'success',
            title: id ? 'Medida actualizada' : 'Medida registrada',
            timer: 2000,
            showConfirmButton: false
        });
    });
});

document.addEventListener('DOMContentLoaded', loadMedidas);
</script>
@endsection
@extends('layout.app')

@section('contents')
<div class="-mx-4 sm:-mx-6 lg:-mx-8 mt-6">

    <!-- Encabezado -->
    <div class="bg-white shadow p-4 flex justify-between items-center w-full">
        <div>
            <h1 class="text-2xl font-semibold text-gray-700">
                Administración de Empleados
            </h1>
        </div>
        <button onclick="openModalCategoria()"
            class="bg-[#6fae2d] hover:bg-[#6fae2d] text-white px-4 py-2 text-sm font-semibold rounded-lg shadow-md flex items-center gap-2">
            <i class="fas fa-plus"></i>
            Agregar nuevo Empleado
        </button>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden w-full mt-4">
        <div class="px-4 py-2 font-semibold text-white shadow-sm" style="background-color:#6fae2d;">
            EMPLEADOS
        </div>

        <div class="p-4">
            <table id="categorias-table" class="w-full text-left border-collapse">
                <thead class="bg-gray-200 text-gray-700 text-sm uppercase">
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Nombre</th>
                        <th class="px-4 py-2">Puesto</th>
                        <th class="px-4 py-2">Estado</th>
                        <th class="px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody id="categorias-table-body" class="text-sm text-gray-700"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= MODAL EMPLEADOS ================= -->
<div id="modalCategoria" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-6 overflow-y-auto max-h-[90vh]">

        <!-- Header -->
        <div class="flex justify-between items-center border-b pb-2 mb-4">
            <h2 id="modalTitle" class="text-lg font-semibold text-gray-700">
                Registrar Empleado
            </h2>
            <button onclick="closeModalCategoria()"
                    class="text-gray-500 hover:text-red-500 text-2xl">&times;</button>
        </div>

        <!-- Formulario -->
        <form id="formCategoria" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <input type="hidden" name="id_empleado" id="empleadoId">

            <div class="md:col-span-2">
                <label class="block text-sm font-medium">Nombre completo</label>
                <input type="text" name="nombre_completo" id="nombre_completo" required
                       class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium">Unidad</label>
                <input type="text" name="unidad" id="unidad"
                       class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium">Puesto</label>
                <input type="text" name="puesto" id="puesto"
                       class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium">Teléfono</label>
                <input type="text" name="telefono" id="telefono"
                       class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium">DPI</label>
                <input type="text" name="dpi" id="dpi"
                       class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium">Sexo</label>
                <select name="sexo" id="sexo" class="w-full border rounded px-3 py-2">
                    <option value="">Seleccione</option>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium">Tipo</label>
                <input type="text" name="tipo" id="tipo"
                       class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium">Estado</label>
                <select name="estado" id="estado" class="w-full border rounded px-3 py-2">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium">Responsable</label>
                <input type="text" name="responsable" id="responsable"
                       class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium">Director</label>
                <input type="text" name="director" id="director"
                       class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium">Edad</label>
                <input type="number" name="edad" id="edad"
                       class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium">Correo</label>
                <input type="email" name="correo" id="correo"
                       class="w-full border rounded px-3 py-2">
            </div>

            <!-- Botones -->
            <div class="md:col-span-2 flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeModalCategoria()"
                        class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded">
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
@endsection
