@extends('layout.app')

@section('contents')
<div class="-mx-4 sm:-mx-6 lg:-mx-8 mt-6">

    <!-- Encabezado -->
    <div class="bg-white shadow p-4 flex flex-wrap justify-between items-center w-full rounded-none">
        <div>
            <h1 class="text-2xl font-semibold text-gray-700">
                Información General
                <span class="text-gray-500 text-lg font-normal">de la Municipalidad</span>
            </h1>
        </div>
        <div class="flex gap-2 mt-3 sm:mt-0">
            <button id="editBtn" class="bg-[#6fae2d] hover:bg-[#6fae2d] text-white px-4 py-2 text-sm font-semibold rounded-lg shadow-md flex items-center gap-2">
                <i class="fas fa-edit"></i> Editar Información
            </button>
            <button id="refreshBtn" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 text-sm font-semibold rounded-lg shadow-md flex items-center gap-2">
                <i class="fas fa-sync"></i> Actualizar
            </button>
        </div>
    </div>

    <!-- Contenedor de información -->
    <div id="empresaInfo" class="bg-white rounded-none shadow-lg overflow-hidden w-full mt-4">
       <div class="px-4 py-2 font-semibold text-white shadow-sm" style="background-color:#6fae2d;">DETALLES DE LA MUNICIPALIDAD.:</div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 overflow-y-auto max-h-[70vh]">

            <!-- Columna izquierda -->
            <div class="space-y-3">
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-700">Nombre:</span>
                    <span id="nombre_empresa" class="text-gray-600">Cargando...</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-700">País:</span>
                    <span id="pais" class="text-gray-600">Cargando...</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-700">Departamento:</span>
                    <span id="departamento" class="text-gray-600">Cargando...</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-700">Municipio:</span>
                    <span id="municipio" class="text-gray-600">Cargando...</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-700">Dirección:</span>
                    <span id="direccion" class="text-gray-600">Cargando...</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-700">Correo:</span>
                    <span id="correo" class="text-gray-600">Cargando...</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold text-gray-700">Moneda:</span>
                    <span id="moneda" class="text-gray-600">Cargando...</span>
                </div>
            </div>

            <!-- Columna derecha -->
            <div class="space-y-3">
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-700">NIT:</span>
                    <span id="nit" class="text-gray-600">Cargando...</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-700">Teléfono:</span>
                    <span id="telefono" class="text-gray-600">Cargando...</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-700">FAX:</span>
                    <span id="fax" class="text-gray-600">Cargando...</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-700">Página Web:</span>
                    <span id="pagina_web" class="text-gray-600">Cargando...</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-700">Última Actualización:</span>
                    <span id="ultima_actualizacion" class="text-gray-600">Cargando...</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-700">Alcalde:</span>
                    <span id="alcalde" class="text-gray-600">Cargando...</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-700">Financiero:</span>
                    <span id="financiero" class="text-gray-600">Cargando...</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="font-semibold text-gray-700">Logo:</span>
                    <span id="logo" class="text-gray-600">Cargando...</span>
                </div>
            </div>

        </div>
    </div>

</div>

<!-- Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 relative">
        <h2 class="text-xl font-semibold mb-4">Editar Información de la Empresa</h2>
        <form id="empresaForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="nombre_empresa" placeholder="Nombre" class="border px-3 py-2 rounded">
                <input type="text" name="pais" placeholder="País" class="border px-3 py-2 rounded">
                <input type="text" name="departamento" placeholder="Departamento" class="border px-3 py-2 rounded">
                <input type="text" name="municipio" placeholder="Municipio" class="border px-3 py-2 rounded">
                <input type="text" name="direccion" placeholder="Dirección" class="border px-3 py-2 rounded">
                <input type="email" name="correo" placeholder="Correo" class="border px-3 py-2 rounded">
                <input type="text" name="telefono" placeholder="Teléfono" class="border px-3 py-2 rounded">
                <input type="text" name="fax" placeholder="Fax" class="border px-3 py-2 rounded">
                <input type="text" name="pagina_web" placeholder="Página Web" class="border px-3 py-2 rounded">
                <input type="text" name="moneda" placeholder="Moneda" class="border px-3 py-2 rounded">
                <input type="text" name="nit" placeholder="NIT" class="border px-3 py-2 rounded">
                <input type="text" name="alcalde" placeholder="Alcalde" class="border px-3 py-2 rounded">
                <input type="text" name="financiero" placeholder="Financiero" class="border px-3 py-2 rounded">
                <input type="file" name="logo" class="border px-3 py-2 rounded col-span-2">
            </div>

            <div class="mt-4 flex justify-end gap-2">
                <button type="button" id="closeModal" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">Cancelar</button>
                <button type="submit" class="bg-[#6fae2d] hover:bg-[#6fae2d] text-white px-4 py-2 rounded">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
const editBtn = document.getElementById('editBtn');
const editModal = document.getElementById('editModal');
const closeModal = document.getElementById('closeModal');
editBtn.addEventListener('click', () => editModal.classList.remove('hidden'));
closeModal.addEventListener('click', () => editModal.classList.add('hidden'));
editModal.addEventListener('click', e => { if(e.target===editModal) editModal.classList.add('hidden'); });

// Cargar datos de la empresa
function loadEmpresa() {
    fetch("{{ route('empresa.data') }}")
        .then(res => res.json())
        .then(data => {
            for(const key in data){
                const el = document.getElementById(key);
                if(el){
                    if(key==='logo'){
                        el.innerHTML = data.logo ? `<img src="${data.logo}" class="h-12">` : 'Sin logo';
                    } else el.textContent = data[key] ?? '';
                }
            }

            const form = document.getElementById('empresaForm');
            for(const key in data){
                const input = form.querySelector(`[name=${key}]`);
                if(input && key !== 'logo') input.value = data[key] ?? '';
            }
        });
}

// Inicializar datos al cargar
loadEmpresa();

// AJAX para actualizar empresa
const empresaForm = document.getElementById('empresaForm');
empresaForm.addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(empresaForm);
    formData.append('_method', 'PUT'); // Para que Laravel reconozca PUT

    fetch("{{ route('empresa.update', 1) }}", {
        method: "POST",
        headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            loadEmpresa();
            editModal.classList.add('hidden');
            alert('Información actualizada correctamente');
        }
    });
});

// Botón actualizar manual
const refreshBtn = document.getElementById('refreshBtn');
refreshBtn.addEventListener('click', loadEmpresa);
</script>
@endsection
