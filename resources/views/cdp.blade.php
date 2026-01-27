@extends('layout.app')

@section('contents')
<div class="-mx-4 sm:-mx-6 lg:-mx-8 mt-6">

    <!-- ENCABEZADO -->
    <div class="bg-white shadow p-4 flex justify-between items-center w-full border-b border-gray-300">
        <div>
            <h1 class="text-2xl font-semibold text-gray-700">
                Constancia de Disponibilidad Presupuestaria
            </h1>
            <p class="text-sm text-gray-500"></p>
        </div>

        <!-- BOTONES -->
        <div class="flex gap-2">
            <button id="btnAgregar"
                class="flex items-center gap-2 bg-[#6fae2d] text-white px-4 py-2 text-sm font-semibold shadow hover:bg-[#5e9826] transition">
                <i class="fas fa-plus"></i> Agregar
            </button>

            <button id="btnFinalizar"
                class="flex items-center gap-2 bg-orange-400 text-white px-4 py-2 text-sm font-semibold shadow hover:bg-orange-400 transition">
                <i class="fas fa-check-circle"></i> Finalizar Operación
            </button>
        </div>
    </div>

    <!-- CONTENIDO -->
    <div class="bg-white shadow-lg border border-gray-300 w-full mt-4 p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- IZQUIERDA -->
            <div class="space-y-4">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="block text-xs font-semibold uppercase">Monto Vigente</span>
                        <span id="montoVigente" class="block border px-3 py-1 bg-gray-50">Q 0.00</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold uppercase">Ejercicio Fiscal</span>
                        <span class="block border px-3 py-1 bg-gray-50">2026</span>
                    </div>
                </div>

                <div>
                    <span class="block text-xs font-semibold uppercase">Monto en Letras</span>
                    <span id="montoLetras" class="block border px-3 py-1 bg-gray-50">
                        CERO QUETZALES EXACTOS
                    </span>
                </div>

                <!-- UNIDAD -->
                <div>
                    <label class="label">Unidad</label>
                    <select class="select select-busqueda" name="id_programa">
                        <option value="">Seleccione</option>
                        @foreach($unidades as $unidad)
                            <option value="{{ $unidad->id_programa }}">
                                {{ $unidad->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- EMPLEADO -->
                <div>
                    <label class="label">Empleado</label>
                    <select class="select select-busqueda" name="id_empleado">
                        <option value="">Seleccione</option>
                        @foreach($empleados as $empleado)
                            <option value="{{ $empleado->id_empleado }}">
                                {{ $empleado->nombre_completo }} — {{ $empleado->puesto }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- MODALIDAD -->
                <div>
                    <label class="label">Modalidad de Compra</label>
                    <select class="select" name="modalidad_compra">
                        <option value="">Seleccione</option>
                        <option value="baja_cuantia">Compra de Baja Cuantía</option>
                        <option value="compra_electronica">Compra Electrónica</option>
                        <option value="no_aplica">No Aplica</option>
                        <option value="cotizacion">Cotización</option>
                        <option value="licitacion">Licitación</option>
                    </select>
                </div>

                <div>
                    <label class="label">Disponibilidad</label>
                    <select class="select">
                        <option>Disponible</option>
                        <option>No Disponible</option>
                    </select>
                </div>

                <div>
                    <label class="label">Fecha de Emisión</label>
                    <input type="date" class="input">
                </div>

                <div>
                    <label class="label">Descripción del Proceso</label>
                    <textarea rows="3" class="input"></textarea>
                </div>
            </div>

            <!-- DERECHA -->
            <div class="border border-gray-300">
                <div class="px-4 py-2 font-semibold text-white bg-[#6fae2d]">
                    ESTRUCTURA PRESUPUESTARIA
                </div>

                <table class="w-full text-xs border-collapse">
                    <thead class="bg-gray-200 uppercase">
                        <tr>
                            <th class="th">Programa</th>
                            <th class="th">SubPrograma</th>
                            <th class="th">Proyecto</th>
                            <th class="th">Actividad</th>
                            <th class="th">Obra</th>
                            <th class="th">Renglón</th>
                            <th class="th">Fuente</th>
                            <th class="th text-right">Monto</th>
                            <th class="th text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaEstructura"></tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- MODAL -->
<div id="modalAgregar"
     class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">

    <div class="bg-white border border-gray-400 w-full max-w-4xl">

        <div class="flex justify-between items-center px-4 py-2 text-white bg-[#6fae2d]">
            <h2 class="text-sm font-bold">
                <i class="fas fa-plus-circle"></i> Estructura Presupuestaria
            </h2>
            <button id="cerrarModal" class="text-lg">&times;</button>
        </div>

        <div class="p-4 grid grid-cols-2 lg:grid-cols-4 gap-2 text-xs">
            <input id="programa" class="input" placeholder="Programa">
            <input id="subprograma" class="input" placeholder="SubPrograma">
            <input id="proyecto" class="input" placeholder="Proyecto">
            <input id="actividad" class="input" placeholder="Actividad">
            <input id="obra" class="input" placeholder="Obra">
            <input id="renglon" class="input" placeholder="Renglón">
            <input id="fuente" class="input" placeholder="Fuente">
            <input id="monto" class="input text-right" type="number" placeholder="Monto">
        </div>

        <div class="flex justify-end gap-2 p-3 border-t">
            <button id="cancelar" class="px-3 py-1 border text-xs">Cancelar</button>
            <button id="guardarFila" class="px-3 py-1 bg-[#6fae2d] text-white text-xs font-semibold">
                <i class="fas fa-save"></i> Guardar
            </button>
        </div>
    </div>
</div>

<!-- SELECT2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
.label{font-size:.75rem;font-weight:600;text-transform:uppercase}
.input,.select{width:100%;border:1px solid #9ca3af;padding:.3rem .5rem}
.input:focus,.select:focus{border-color:#6fae2d}
.th{border:1px solid #9ca3af;padding:.35rem;font-weight:700}
.td{border:1px solid #d1d5db;padding:.35rem}

/* ajuste visual select2 */
.select2-container--default .select2-selection--single {
    height: 34px;
    border: 1px solid #9ca3af;
    border-radius: 0;
}
.select2-selection__rendered {
    line-height: 32px !important;
}
.select2-selection__arrow {
    height: 32px !important;
}
</style>

<!-- SELECT2 JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
let total = 0;
let filaEditando = null;
const modal = document.getElementById('modalAgregar');
const campos = ['programa','subprograma','proyecto','actividad','obra','renglon','fuente','monto'];

const limpiarModal = () => campos.forEach(c => document.getElementById(c).value = '');

btnAgregar.onclick = () => {
    filaEditando = null;
    limpiarModal();
    modal.classList.remove('hidden');
};

cerrarModal.onclick = cancelar.onclick = () => modal.classList.add('hidden');

guardarFila.onclick = () => {
    const valores = campos.map(c => document.getElementById(c).value || '—');
    const monto = parseFloat(valores[7]) || 0;

    if (filaEditando) {
        filaEditando.querySelectorAll('td').forEach((td,i)=>{
            if(i < 7) td.textContent = valores[i];
            if(i === 7) td.textContent = `Q ${monto.toFixed(2)}`;
        });
    } else {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            ${valores.slice(0,7).map(v=>`<td class="td">${v}</td>`).join('')}
            <td class="td text-right font-semibold">Q ${monto.toFixed(2)}</td>
            <td class="td text-center">
                <button class="editar text-orange-400 mr-1"><i class="fas fa-edit"></i></button>
                <button class="eliminar text-red-600"><i class="fas fa-trash"></i></button>
            </td>`;
        tablaEstructura.appendChild(tr);
    }

    recalcularTotal();
    limpiarModal();
    modal.classList.add('hidden');
};

tablaEstructura.onclick = e => {
    if (e.target.closest('.editar')) {
        filaEditando = e.target.closest('tr');
        filaEditando.querySelectorAll('td').forEach((td,i)=>{
            if(i < campos.length) document.getElementById(campos[i]).value = td.textContent.replace('Q ','');
        });
        modal.classList.remove('hidden');
    }

    if (e.target.closest('.eliminar')) {
        e.target.closest('tr').remove();
        recalcularTotal();
    }
};

function recalcularTotal(){
    total = 0;
    document.querySelectorAll('#tablaEstructura tr').forEach(tr=>{
        total += parseFloat(tr.children[7].textContent.replace('Q ',''));
    });
    montoVigente.textContent = `Q ${total.toFixed(2)}`;
    montoLetras.textContent = numeroALetras(total);
}

function numeroALetras(num){
    if(num === 0) return 'CERO QUETZALES EXACTOS';
    return num.toFixed(2)+' QUETZALES';
}

/* ACTIVAR BUSQUEDA */
$(document).ready(function () {
    $('.select-busqueda').select2({
        placeholder: 'Seleccione',
        allowClear: true,
        width: '100%'
    });
});
btnFinalizar.onclick = () => {

    if (total <= 0) {
        alert('Debe agregar al menos una estructura presupuestaria');
        return;
    }

    let detalles = [];

    document.querySelectorAll('#tablaEstructura tr').forEach(tr => {
        detalles.push({
            programa: tr.children[0].textContent,
            subprograma: tr.children[1].textContent,
            proyecto: tr.children[2].textContent,
            actividad: tr.children[3].textContent,
            obra: tr.children[4].textContent,
            renglon: tr.children[5].textContent,
            fuente: tr.children[6].textContent,
            monto: tr.children[7].textContent.replace('Q ', '')
        });
    });

    const data = {
        _token: '{{ csrf_token() }}',
        id_programa: document.querySelector('[name="id_programa"]').value,
        id_empleado: document.querySelector('[name="id_empleado"]').value,
        modalidad: document.querySelector('[name="modalidad_compra"]').value,
        tipo_disponibilidad: document.querySelector('.select:not([name])').value,
        fecha: document.querySelector('input[type="date"]').value,
        descripcion: document.querySelector('textarea').value,
        monto: total,
        detalles: detalles
    };

    fetch('{{ route("cdp.store") }}', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify(data)
})

    .then(r => r.json())
    .then(res => {
    if(res.ok){
        Swal.fire({
            icon: 'success',
            title: 'Operación exitosa',
            text: 'La Constancia de Disponibilidad Presupuestaria fue registrada correctamente',
            confirmButtonColor: '#6fae2d'
        }).then(() => {
            location.reload();
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: res.mensaje || 'No se pudo guardar la información',
            confirmButtonColor: '#d33'
        });
    }
});

};
</script>

@endsection
