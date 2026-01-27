    @extends('layout.app')

    @section('contents')
        <style>
            .autocomplete-list{
            position:absolute;
            top:100%;
            left:0;
            background:white;
            border:1px solid #e5e7eb;
            border-radius:6px;
            width:220px;
            max-height:180px;
            overflow-y:auto;
            box-shadow:0 8px 20px rgba(0,0,0,.08);
            z-index:9999;
            }

            .autocomplete-item{
            padding:6px 10px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            font-size:13px;
            cursor:pointer;
            }

            .autocomplete-item strong{
            font-weight:500;
            color:#111827;
            max-width:140px;
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
            }

            .autocomplete-item span{
            font-size:12px;
            color:#6b7280;
            }

            .autocomplete-item:hover{
            background:#f9fafb;
            }
        </style>

    <div class="-mx-4 sm:-mx-6 lg:-mx-8 mt-6">

        <!-- Encabezado -->
        <div class="bg-white shadow p-4 flex justify-between items-center w-full">
            <div>
                <h1 class="text-2xl font-semibold text-gray-700">
                    Administración de Productos.:
                </h1>
            </div>
            <button onclick="openModalProducto()"
                class="bg-[#6fae2d] hover:bg-[#6fae2d] text-white px-4 py-2 text-sm font-semibold rounded-lg shadow-md flex items-center gap-2">
                <i class="fas fa-plus"></i>
                Agregar nuevo Producto.:
            </button>
        </div>

        <!-- Tabla de productos -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden w-full mt-4">
            <div class="px-4 py-2 font-semibold text-white shadow-sm" style="background-color:#6fae2d;">
                PRODUCTOS
            </div>

            <div class="p-4">
                <table id="productos-table" class="w-full text-left border-collapse">
                    <thead class="bg-gray-200 text-gray-700 text-sm uppercase">
                        <tr>
                            <th class="px-4 py-2">Codigo</th>
                            <th class="px-4 py-2">Nombre</th>
                            <th class="px-4 py-2">Renglón</th>
                            <th class="px-4 py-2">Ubicación</th>
                            <th class="px-4 py-2">Stock</th>
                            <th class="px-4 py-2">Precio</th>
                            <th class="px-4 py-2">Estado</th>
                            <th class="px-4 py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="productos-table-body" class="text-sm text-gray-700"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL CREAR / EDITAR PRODUCTO -->
    <div id="modalProducto" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-3/4 lg:w-2/3 p-6">

            <div class="flex justify-between items-center border-b pb-2 mb-4">
                <h2 id="modalTitle" class="text-lg font-semibold text-gray-700">
                    Registrar Producto
                </h2>
                <button onclick="closeModalProducto()" class="text-gray-500 hover:text-red-500 text-xl">
                    &times;
                </button>
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-600">
                    Código de Producto
                </label>
                <div id="nextProductoId"
                    class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2 text-sm font-semibold text-gray-700">
                    —
                </div>
            </div>

            <form id="formProducto" class="space-y-6">
                @csrf
                <input type="hidden" name="id_producto" id="productoId">
                <input type="hidden" name="usuario" value="{{ auth()->user()->name }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Datos generales -->
                    <div class="border p-4 rounded-lg">
                        <h3 class="font-semibold mb-4 text-gray-700">Datos Generales</h3>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-600">Renglón</label>
                            <select name="id_renglon" id="id_renglon" required
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm"></select>
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-600">Categoría</label>
                            <select name="id_categoria" id="id_categoria" required
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm"></select>
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-600">Medida</label>
                            <select name="id_medida" id="id_medida" required
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm"></select>
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-600">Ubicación</label>
                            <select name="id_ubicacion" id="id_ubicacion" required
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm"></select>
                        </div>
                    </div>

                    <!-- Información comercial -->
                    <div class="border p-4 rounded-lg">
                        <h3 class="font-semibold mb-4 text-gray-700">Información Comercial</h3>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-600">Nombre</label>
                            <input type="text" name="nombre" id="nombre" required
                                class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-600">Marca</label>
                            <input type="text" name="marca" id="marca"
                                class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-600">Precio</label>
                            <input type="number" step="0.01" name="precio" id="precio" required
                                class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-600">Detalle</label>
                            <textarea name="detalle" id="detalle" rows="3"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-600">Stock</label>
                            <input type="number" name="stock" id="stock" min="0" required
                                class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-600">Estado</label>
                            <select name="estado" id="estado"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" onclick="closeModalProducto()"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="bg-[#6fae2d] hover:bg-[#6fae2d] text-white px-4 py-2 rounded">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- LIBRERÍAS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <script>
    let nextProductoId = null;

    let productosTable;

    function openModalProducto(edit=false, producto=null){
    document.getElementById('formProducto').reset();
    productoId.value = '';
    modalTitle.textContent = edit ? 'Editar Producto' : 'Registrar Producto';

    // ===== MOSTRAR ID =====
    const labelId = document.getElementById('nextProductoId');

    if(edit && producto){
        productoId.value = producto.id_producto;
        nombre.value = producto.nombre;
        marca.value = producto.marca;
        precio.value = producto.precio;
        detalle.value = producto.detalle;
        stock.value = producto.stock;
        estado.value = producto.estado;
        id_renglon.value = producto.id_renglon;
        id_categoria.value = producto.id_categoria;
        id_medida.value = producto.id_medida;
        id_ubicacion.value = producto.id_ubicacion;

        labelId.textContent = producto.id_producto;
    } else {
        labelId.textContent = nextProductoId ?? '—';
    }

    modalProducto.classList.remove('hidden');
}


    function closeModalProducto(){
        modalProducto.classList.add('hidden');
    }

    function loadSelects(){
        fetch("{{ route('renglones.getAll') }}")
            .then(r => r.json())
            .then(d => {
                id_renglon.innerHTML = d
                    .map(x => `
                        <option value="${x.id_renglon}">
                            ${x.codigo ?? x.renglon} - ${x.nombre}
                        </option>
                    `)
                    .join('');
            });

        fetch("{{ route('categorias.getAll') }}")
            .then(r=>r.json())
            .then(d=>{
                id_categoria.innerHTML = d
                    .map(x=>`<option value="${x.id_categoria}">${x.descripcion}</option>`)
                    .join('');
            });

        fetch("{{ route('medidas.getAll') }}")
            .then(r=>r.json())
            .then(d=>{
                id_medida.innerHTML = d
                    .map(x=>`<option value="${x.id_medida}">${x.descripcion}</option>`)
                    .join('');
            });

        fetch("{{ route('ubicaciones.getAll') }}")
            .then(r=>r.json())
            .then(d=>{
                id_ubicacion.innerHTML = d
                    .map(x=>`<option value="${x.id_ubicacion}">${x.descripcion}</option>`)
                    .join('');
            });
    }

    function initProductoAutocomplete(data){
  const wrapper = document.createElement('div');
  wrapper.className = "relative flex items-center gap-2 ml-4";

  wrapper.innerHTML = `
    <label class="text-sm text-gray-600 font-medium">Producto:</label>
    <input type="text" id="productoFiltro"
      class="border rounded px-3 py-1 text-sm w-64"
      placeholder="Buscar producto..."
      autocomplete="off">
    <div id="productoList" class="autocomplete-list hidden"></div>
  `;

  document.querySelector('#productos-table_wrapper .dataTables_length')
    .appendChild(wrapper);

  const input = document.getElementById('productoFiltro');
  const list  = document.getElementById('productoList');

  input.addEventListener('input', ()=>{
    const val = input.value.toLowerCase();
    list.innerHTML = '';

    if(!val){
      list.classList.add('hidden');
      productosTable.search('').draw();
      return;
    }

    const matches = data.filter(p =>
      p.nombre.toLowerCase().includes(val)
    );

    if(!matches.length){
      list.classList.add('hidden');
      return;
    }

    matches.forEach(p=>{
      const item = document.createElement('div');
      item.className = 'autocomplete-item';
      item.innerHTML = `
        <strong>${p.nombre}</strong>
        <span class="text-gray-500 text-xs"> — Q ${parseFloat(p.precio).toFixed(2)}</span>
      `;
      item.onclick = ()=>{
        input.value = p.nombre;
        list.classList.add('hidden');
        productosTable.search(p.nombre).draw();
      };
      list.appendChild(item);
    });

    list.classList.remove('hidden');
  });

  document.addEventListener('click', e=>{
    if(!wrapper.contains(e.target)){
      list.classList.add('hidden');
    }
  });
}

    function loadProductos(){
        fetch("{{ route('productos.getAll') }}")
            .then(res=>res.json())
            .then(data=>{
                if(productosTable) productosTable.clear().destroy();

                const tbody = document.getElementById('productos-table-body');
                tbody.innerHTML = '';

                // ===== CALCULAR SIGUIENTE ID =====
                if (data.length > 0) {
                    const maxId = Math.max(...data.map(p => parseInt(p.id_producto)));
                    nextProductoId = maxId + 1;
                } else {
                    nextProductoId = 1;
                }

                data.forEach(p=>{

                    // ===== ESTADO =====
                    const estadoLabel = p.estado
                        ? `<span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Activo</span>`
                        : `<span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Inactivo</span>`;

                    // ===== STOCK VISUAL =====
                    const stockLabel = p.stock <= 0
                        ? `<span class="text-red-600 font-semibold flex items-center justify-center gap-1">
                                <i class="fas fa-exclamation-triangle"></i> ${p.stock}
                        </span>`
                        : `<span class="text-green-600 font-semibold flex items-center justify-center gap-1">
                                <i class="fas fa-check-circle"></i> ${p.stock}
                        </span>`;

                    // ===== RENGLÓN (SEGURO) =====
                    const renglonNombre = p.renglon?.nombre ?? '—';

                    tbody.innerHTML += `
                        <tr>
                            <td class="text-center">${p.id_producto}</td>
                            <td>${p.nombre}</td>
                            <td class="text-center">${p.renglon}</td>
                            <td>${p.ubicacion?.descripcion ?? p.ubicacion ?? '—'}</td>
                            <td class="text-center">${stockLabel}</td>
                            <td class="text-center">Q ${parseFloat(p.precio).toFixed(2)}</td>
                            <td class="text-center">${estadoLabel}</td>
                            <td class="text-center">
                                <button
                                class="text-orange-400 btn-edit"
                                title="Editar"
                                data-producto='${JSON.stringify(p)}'>
                                <i class="fas fa-edit"></i>
                                </button>

                                <button
                                    class="text-red-600 btn-toggle ml-2"
                                    title="Cambiar estado"
                                    data-id="${p.id_producto}">
                                    <i class="fas fa-exchange-alt"></i>
                                </button>

                                <button
                                    class="text-blue-800 btn-kardex ml-2"
                                    title="Ver Kardex"
                                    data-id="${p.id_producto}">
                                    <i class="fa-solid fa-layer-group"></i>
                                </button>


                            </td>
                        </tr>
                    `;
                });

                productosTable = $('#productos-table').DataTable({
                    autoWidth: false,
                    columnDefs: [
                        { width: "8%", targets: 0, className: "text-center" },   // Código
                        { width: "38%", targets: 1 },                            // Nombre
                        { width: "10%", targets: 2, className: "text-center" },  // Renglón
                        { width: "12%", targets: 3 },                            // Ubicación
                        { width: "5%", targets: 4, className: "text-center" },   // Stock
                        { width: "10%", targets: 5, className: "text-center" },  // Precio
                        { width: "10%", targets: 6, className: "text-center" },  // Estado
                        { width: "7%", targets: 7, className: "text-center" }    // Acciones
                    ]
                });
                initProductoAutocomplete(data);
            });
    }

    document.addEventListener('click', e=>{
        if(e.target.closest('.btn-edit')){
            openModalProducto(true, JSON.parse(e.target.closest('.btn-edit').dataset.producto));
        }

        if(e.target.closest('.btn-toggle')){
            const id = e.target.closest('.btn-toggle').dataset.id;
            fetch(`/productos/toggle-estado/${id}`,{
                method:'PATCH',
                headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}
            }).then(()=>loadProductos());
        }
        // ===== VER KARDEX =====
        if (e.target.closest('.btn-kardex')) {
            const idProducto = e.target.closest('.btn-kardex').dataset.id;
            window.location.href = `/kardex/${idProducto}`;
        }
    });


    formProducto.addEventListener('submit', e=>{
        e.preventDefault();

        const id = productoId.value;
        const url = id ? `/productos/${id}` : `{{ route('productos.store') }}`;
        const data = new FormData(formProducto);

        if(id) data.append('_method','PUT');

        fetch(url,{
            method:'POST',
            headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body:data
        }).then(()=>{
            closeModalProducto();
            loadProductos();
            Swal.fire('Correcto','Producto guardado','success');
        });
    });

    function initEmptyTable(){
    productosTable = $('#productos-table').DataTable({
        autoWidth: false,
        data: [],
        columnDefs: [
            { width: "8%", targets: 0, className: "text-center" },
            { width: "38%", targets: 1 },
            { width: "10%", targets: 2, className: "text-center" },
            { width: "12%", targets: 3 },
            { width: "5%", targets: 4, className: "text-center" },
            { width: "10%", targets: 5, className: "text-center" },
            { width: "10%", targets: 6, className: "text-center" },
            { width: "7%", targets: 7, className: "text-center" }
        ]
    });
}

    document.addEventListener('DOMContentLoaded', ()=>{
        loadSelects();
        loadProductos();
    });
    </script>


    @endsection
