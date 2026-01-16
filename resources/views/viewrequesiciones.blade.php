@extends('layout.app')
@section('contents')
<!-- ================= TAILWIND ================= -->
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          primary: '#6fae2d',
          primaryDark: '#5b9325',
          primarySoft: '#eaf4df',
          primaryBorder: '#cfe3b8',
          primaryText: '#4d7f1f'
        }
      }
    }
  }
</script>
<style>
  .spinner { animation: spin 1s linear infinite; }
  @keyframes spin { 100% { transform: rotate(360deg); } }

  /* ===== AUTOCOMPLETE ===== */
  .autocomplete-box{ position: relative; width: 100%; }
  .autocomplete-list{ position:absolute; top:100%; left:0; width:100%; background:white; border:1px solid #cfe3b8; border-radius:8px; max-height:180px; overflow-y:auto; z-index:9999; box-shadow:0 8px 20px rgba(0,0,0,.08); }
  .autocomplete-item{ padding:8px 12px; cursor:pointer; font-size:14px; }
  .autocomplete-item:hover{ background:#eaf4df; }
</style>
<script>
  function validando() {
    const btn = document.getElementById('btnValidar');
    btn.disabled = true;
    btn.classList.add('opacity-80');
  }
</script>

<form method="POST" action="{{ route('requisiciones.store') }}">
  @csrf
 <div class="bg-primarySoft px-2 py-2 lg:px-4 lg:py-3">

    <!-- ================= HEADER ================= -->
    <div class="rounded-xl p-4 mb-4 shadow bg-primary">
      <h1 class="text-2xl font-bold text-white">Proceso de Requisición</h1>
      <span class="text-sm text-white/90">Registro del Proceso</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
      <!-- ================= COLUMNA IZQUIERDA ================= -->
      <div class="lg:col-span-5">
        <div class="bg-[#f8fbf4] border-2 border-primary rounded-xl p-4 h-full flex flex-col">
          <span class="px-3 py-2 bg-orange-200 border border-orange-500 text-sm font-semibold rounded w-fit"> En Creación </span>

          <div class="text-right text-sm font-semibold my-3">
            Número de Solicitud<br>
            @php
            use App\Models\Requisicion;
            $siguienteId = (Requisicion::max('id_requisicion') ?? 0) + 1;
            @endphp

            <span class="text-gray-700">
            SOL-{{ str_pad($siguienteId, 4, '0', STR_PAD_LEFT) }}
            </span>


          </div>

          <h2 class="text-xl font-bold text-primary">Proceso de Requisición</h2>
          <p class="text-sm font-semibold text-gray-800 mb-4">Registro del Proceso</p>

          <div class="grid grid-cols-3 gap-3 mb-4">
            <div>
              <label class="text-sm font-semibold">Fecha</label>
              <input type="date" name="fecha" class="w-full border border-primary rounded px-2 py-1" required>
            </div>
            <div class="col-span-2">
              <label class="text-sm font-semibold">Tipo de Solicitud</label>
              <select name="tipo_solicitud" class="w-full border border-primary rounded px-2 py-1" required>
                <option value="">Seleccione tipo</option>
                <option value="compra">Compra</option>
                <option value="servicio">Servicio</option>
              </select>
            </div>
          </div>

          <div class="border-t-2 border-primary my-3"></div>

          <!-- ================= SOLICITANTE ================= -->
          <label class="text-sm font-semibold">Datos del Solicitante:</label>
          <div class="flex gap-2 mt-2 min-w-0">

            <!-- SELECT REAL (oculto) -->
            <select id="selectEmpleado" name="id_empleado" class="hidden"></select>

            <!-- INPUT AUTOCOMPLETE -->
            <div class="autocomplete-box flex-1">
              <input type="text" id="inputEmpleado" placeholder="Buscar empleado..." class="w-full border border-primary rounded px-2 py-1 bg-white" required>
              <div id="listEmpleado" class="autocomplete-list hidden"></div>
            </div>

            <button type="button" id="btnLimpiarEmpleado" class="border border-primary rounded px-3 text-primary hover:bg-primarySoft"> 🗑 </button>
          </div>

          <div class="grid grid-cols-2 gap-3 mt-3">
        <div>
            <label class="text-sm font-semibold">Unidad:</label>
            <div id="unidadEmpleado"
                class="w-full border border-primary bg-primarySoft rounded px-2 py-1 text-sm text-gray-800">
            —
            </div>
        </div>

        <div>
            <label class="text-sm font-semibold">Puesto:</label>
            <div id="puestoEmpleado"
                class="w-full border border-primary bg-primarySoft rounded px-2 py-1 text-sm text-gray-800">
            —
            </div>
        </div>
        </div>


          <div class="border-t-2 border-primary my-3"></div>

          <!-- ================= PROGRAMA ================= -->
          <label class="text-sm font-semibold">Seleccione el Programa de destino</label>
          <div class="flex gap-2 mt-2 min-w-0">
            <!-- SELECT REAL -->
            <select id="selectPrograma" name="id_programa" class="hidden"></select>

            <!-- INPUT AUTOCOMPLETE -->
            <div class="autocomplete-box flex-1 min-w-0">
              <input type="text" id="inputPrograma" placeholder="Buscar programa..." class="w-full bg-white border-2 border-primary rounded px-3 py-2" required>
              <div id="listPrograma" class="autocomplete-list hidden"></div>
            </div>

            <div class="grid grid-cols-2 gap-1 w-10 h-10 shrink-0">
              <span class="bg-primary"></span>
              <span class="bg-primary"></span>
              <span class="bg-primary"></span>
              <span class="bg-primary"></span>
            </div>
          </div>

          <div class="border-t-2 border-primary my-3"></div>

          <label class="text-sm font-semibold">Detalle de la Solicitud:</label>
          <textarea name="detalle"
  rows="6"
  class="border border-primary rounded px-3 py-2 mt-2 resize-none text-sm focus:outline-none focus:ring-2 focus:ring-primary">
</textarea>


       <button id="btnValidar" type="button" onclick="guardarRequisicion()"
            class="mt-4 bg-primary text-white font-semibold py-2 rounded flex items-center justify-center gap-2">
            <i class="fa-solid fa-circle-check"></i>
            <span>Validar</span>
        </button>


        </div>
      </div>

      <!-- ================= COLUMNA DERECHA ================= -->
      <div class="lg:col-span-7">
        <div class="bg-white border-2 border-primaryBorder rounded-xl p-4 h-full">
          <label class="text-sm text-primaryText">Detalle de Productos</label>
          <select id="selectProducto" class="hidden"></select>

<!-- AUTOCOMPLETE -->
<div class="autocomplete-box mb-4">
  <input type="text" id="inputProducto"
         placeholder="Buscar producto..."
         class="w-full border border-primary rounded px-3 py-2 bg-white">
  <div id="listProducto" class="autocomplete-list hidden"></div>
</div>


          <div class="overflow-x-auto mb-4">
            <table class="w-full border border-gray-300 table-fixed">

            <!-- CONTROL DE TAMAÑOS -->
            <colgroup>
                <col style="width: 10%"> <!-- Código -->
                <col style="width: 40%"> <!-- Producto -->
                <col style="width: 10%"> <!-- Cant -->
                <col style="width: 10%"> <!-- C/U -->
                <col style="width: 15%"> <!-- SubTotal -->
                <col style="width: 5%">  <!-- Eliminar -->
            </colgroup>

            <thead class="bg-primary text-white">
                <tr>
                <th class="border p-2">Código</th>
                <th class="border p-2">Producto</th>
                <th class="border p-2 text-center">Cant</th>
                <th class="border p-2 text-right">C/U</th>
                <th class="border p-2 text-right">SubTotal</th>
                <th class="border p-2 text-center">🗑</th>
                </tr>
            </thead>

            <tbody id="tablaProductos">
                <tr id="filaVacia">
                <td colspan="6" class="text-center p-4 text-gray-400">
                    Sin productos agregados
                </td>
                </tr>
            </tbody>
            </table>

          </div>

            <div class="flex justify-between items-center rounded-lg p-3 text-white shadow" style="background:#F5A927">
            <span class="text-lg font-semibold">Total</span>
            <span id="totalGeneral" class="text-2xl font-bold">Q 0.00</span>
            </div>


         <button id="btnGenerar" onclick="validando()"
        class="w-full mt-4 bg-primary text-white font-semibold py-3 rounded flex items-center justify-center gap-2">

        <i class="fa-solid fa-file-circle-plus"></i>
        <span>Generar</span>

        </button>

        </div>
      </div>
    </div>
  </div>
</form>

<script>
/* ================= INICIO ================= */

document.addEventListener('DOMContentLoaded', () => {
  cargarEmpleados();
  cargarProgramas();
  cargarProductos();

  document.getElementById('btnLimpiarEmpleado')
    ?.addEventListener('click', limpiarEmpleado);
});

/* ================= VARIABLES ================= */

let empleadosData = [];
let productosData = [];

/* ================= AUTOCOMPLETE ================= */

function activarAutocomplete(inputId, listId, selectId, onSelect = null) {
  const input = document.getElementById(inputId);
  const list = document.getElementById(listId);
  const select = document.getElementById(selectId);

  input.addEventListener('input', () => {
    const text = input.value.toLowerCase();
    list.innerHTML = '';

    if (!text) {
      list.classList.add('hidden');
      return;
    }

    [...select.options].forEach(opt => {
      if (opt.value && opt.text.toLowerCase().includes(text)) {
        const div = document.createElement('div');
        div.className = 'autocomplete-item';
        div.textContent = opt.text;

        div.onclick = () => {
            input.value = opt.text;      // ✅ MOSTRAR NOMBRE
            select.value = opt.value;    // ✅ GUARDAR ID
            list.classList.add('hidden');

            if (onSelect) onSelect(opt.value);
            };


        list.appendChild(div);
      }
    });

    list.classList.remove('hidden');
  });

  document.addEventListener('click', e => {
    if (!input.contains(e.target) && !list.contains(e.target)) {
      list.classList.add('hidden');
    }
  });
}

/* ================= EMPLEADOS ================= */

function cargarEmpleados() {
  fetch("{{ url('/requisiciones/ajax/empleados') }}")
    .then(r => r.json())
    .then(data => {
      empleadosData = data;
      const select = document.getElementById('selectEmpleado');
      select.innerHTML = '<option value="">Seleccione empleado</option>';

      data.forEach(e => {
        select.innerHTML += `<option value="${e.id_empleado}">${e.nombre_completo}</option>`;
      });

      activarAutocomplete('inputEmpleado', 'listEmpleado', 'selectEmpleado', cargarDatosEmpleado);
    });
}

function cargarDatosEmpleado(id) {
  const e = empleadosData.find(x => x.id_empleado == id);
  document.getElementById('unidadEmpleado').textContent = e?.unidad || '—';
  document.getElementById('puestoEmpleado').textContent = e?.puesto || '—';
}

function limpiarEmpleado() {
  inputEmpleado.value = '';
  selectEmpleado.value = '';
  unidadEmpleado.textContent = '—';
  puestoEmpleado.textContent = '—';
}

/* ================= PROGRAMAS ================= */

function cargarProgramas() {
  fetch("{{ url('/requisiciones/ajax/programas') }}")
    .then(r => r.json())
    .then(data => {
      const select = document.getElementById('selectPrograma');
      select.innerHTML = '<option value="">Seleccione programa</option>';
      data.forEach(p => select.innerHTML += `<option value="${p.id_programa}">${p.nombre}</option>`);
      activarAutocomplete('inputPrograma', 'listPrograma', 'selectPrograma');
    });
}

/* ================= PRODUCTOS ================= */

function cargarProductos() {
  fetch("{{ url('/requisiciones/ajax/productos') }}")
    .then(r => r.json())
    .then(data => {
      productosData = data;
      const select = document.getElementById('selectProducto');
      select.innerHTML = '<option value="">Seleccione producto</option>';

      data.forEach(p => {
        select.innerHTML += `<option value="${p.id_producto}">${p.nombre}</option>`;
      });

      activarAutocomplete('inputProducto', 'listProducto', 'selectProducto', agregarProducto);
    });
}

/* ================= TABLA ================= */

function agregarProducto(idProducto) {
  const producto = productosData.find(p => p.id_producto == idProducto);
  if (!producto) return;

  const tbody = document.getElementById('tablaProductos');

  const filaVacia = document.getElementById('filaVacia');
  if (filaVacia) filaVacia.remove();

  const tr = document.createElement('tr');
  tr.innerHTML = `
    <td class="border p-2 text-center">${producto.id_producto}</td>
    <td class="border p-2">${producto.nombre}</td>

    <!-- ✅ CANTIDAD CON SWEET ALERT -->
    <td class="border p-2 text-center">
      <button onclick="cambiarCantidad(this)"
        class="px-3 py-1 border rounded bg-primarySoft font-semibold hover:bg-primaryBorder">
        1
      </button>
    </td>

    <td class="border p-2 text-right">Q ${parseFloat(producto.precio).toFixed(2)}</td>

    <td class="border p-2 text-right subtotal">
      Q ${parseFloat(producto.precio).toFixed(2)}
    </td>

    <td class="border p-2 text-center">
      <button onclick="eliminarFila(this)"
  class="text-red-600 hover:text-red-800 text-xl">
  <i class="fa-solid fa-trash"></i>
</button>

    </td>
  `;

  tbody.appendChild(tr);
  recalcularTotal();

    document.getElementById('inputProducto').value = '';
  document.getElementById('selectProducto').value = '';
}


/* ================= SWEET ALERT CANTIDAD ================= */

function cambiarCantidad(btn) {
  Swal.fire({
    title: 'Ingrese la cantidad.:',
    input: 'number',
    inputValue: btn.textContent,
    inputAttributes: { min: 1 },
    showCancelButton: true,
    confirmButtonText: 'Aceptar'
  }).then(result => {
    if (result.isConfirmed && result.value > 0) {
      btn.textContent = result.value;
      const tr = btn.closest('tr');
      const precio = parseFloat(tr.children[3].textContent.replace('Q',''));
      tr.querySelector('.subtotal').textContent =
        `Q ${(precio * result.value).toFixed(2)}`;
      recalcularTotal();
    }
  });
}

/* ================= ELIMINAR ================= */

function eliminarFila(btn) {
  btn.closest('tr').remove();

  const tbody = document.getElementById('tablaProductos');
  if (!tbody.children.length) {
  tbody.innerHTML = `
    <tr id="filaVacia">
      <td colspan="6" class="text-center p-4 text-gray-400">
        Sin productos agregados
      </td>
    </tr>`;
}

  recalcularTotal();
}

/* ================= TOTAL ================= */

function recalcularTotal() {
  let total = 0;

  document.querySelectorAll('.subtotal').forEach(td => {
    total += parseFloat(td.textContent.replace('Q', ''));
  });

  document.getElementById('totalGeneral').textContent =
    `Q ${total.toFixed(2)}`;
}

function guardarRequisicion() {

    const btn = document.getElementById('btnValidar');
    btn.disabled = true;
    btn.classList.add('opacity-70');

    // ================== PRODUCTOS ==================
    const productos = [];

    document.querySelectorAll('#tablaProductos tr').forEach(tr => {
        if (tr.id === 'filaVacia') return;

        const tds = tr.querySelectorAll('td');

        productos.push({
            id_producto: parseInt(tds[0].textContent.trim()),
            cantidad: parseInt(tds[2].innerText.trim()),
            precio_unitario: parseFloat(tds[3].textContent.replace('Q', '')),
        });
    });

    const data = {
        _token: document.querySelector('input[name="_token"]').value,
        id_empleado: document.getElementById('selectEmpleado').value,
        id_programa: document.getElementById('selectPrograma').value,
        tipo_solicitud: document.querySelector('[name="tipo_solicitud"]').value,
        fecha: document.querySelector('[name="fecha"]').value,
        descripcion: document.querySelector('[name="detalle"]').value,
        productos: productos
    };

    fetch("{{ route('requisiciones.store') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': data._token
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(resp => {

        if (!resp.ok) {
            Swal.fire('Error', resp.error ?? 'No se pudo guardar', 'error');
            btn.disabled = false;
            return;
        }

        Swal.fire({
            icon: 'success',
            title: 'Requisición creada',
            text: 'La requisición y sus productos fueron guardados',
            timer: 2000,
            showConfirmButton: false
        });
    })
    .catch(err => {
        console.error(err);
        btn.disabled = false;
        Swal.fire('Error', 'Revisa consola y log', 'error');
    });
}

/* ================= FIN ================= */
</script>



@endsection
