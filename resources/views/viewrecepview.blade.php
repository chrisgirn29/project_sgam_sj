@extends('layout.app')

@section('contents')

@php
    $esRecepcion = isset($requisicion);
@endphp

<!-- LIBRERÍAS -->
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- SELECT2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#6fae2d',
                    primarySoft: '#eaf4df',
                    primaryBorder: '#cfe3b8',
                }
            }
        }
    }
</script>

<form id="formRecepcion" method="POST" action="{{ route('recepciones.store') }}">
    @csrf

    <!-- CAMPOS CLAVE -->
    <input type="hidden" name="id_requisicion" value="{{ $requisicion->id_requisicion }}">
    <input type="hidden" name="id_empleado" value="{{ $requisicion->id_empleado }}">
    <input type="hidden" name="id_programa" value="{{ $requisicion->id_programa }}">

    <!-- CAMPOS SWEETALERT -->
    <input type="hidden" name="serie_factura" id="serie_factura">
    <input type="hidden" name="numero_factura" id="numero_factura">
    <input type="hidden" name="forma_pago" id="forma_pago">
    <input type="hidden" name="numero_documento" id="numero_documento">

    <div class="bg-primarySoft px-4 py-4">

        <!-- HEADER -->
        <div class="rounded-xl p-4 mb-4 shadow bg-primary">
            <h1 class="text-2xl font-bold text-white">
                Proceso de Recepción
            </h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

            <!-- IZQUIERDA -->
            <div class="lg:col-span-5">
                <div class="bg-white border-2 border-primary rounded-xl p-4">

                    <div class="mb-3">
                        <label class="text-sm font-semibold">Fecha Solicitud</label>
                        <input type="date"
                               class="w-full border border-primary rounded px-2 py-1"
                               value="{{ $requisicion->fecha }}"
                               readonly>
                    </div>

                    <div class="mb-3">
                        <label class="text-sm font-semibold">Fecha de Recepción</label>
                        <input type="date"
                               name="fecha_recepcion"
                               class="w-full border border-primary rounded px-2 py-1"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="text-sm font-semibold">Proveedor</label>
                        <select name="id_proveedor"
                                id="proveedor"
                                class="w-full border border-primary rounded px-2 py-1"
                                required>
                            <option value="">Seleccione un proveedor</option>
                            @foreach($proveedores as $p)
                                <option value="{{ $p->id_proveedor }}">{{ $p->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <label class="text-sm font-semibold">Solicitante</label>
                    <input type="text"
                           class="w-full border border-primary rounded px-2 py-1 bg-white"
                           value="{{ $requisicion->empleado->nombre_completo }}"
                           readonly>

                    <label class="text-sm font-semibold mt-4 block">Programa</label>
                    <input type="text"
                           class="w-full border border-primary rounded px-2 py-1 bg-white"
                           value="{{ $requisicion->programa->nombre }}"
                           readonly>

                    <label class="text-sm font-semibold mt-4 block">Descripción</label>
                    <textarea rows="3"
                              class="w-full border border-primary rounded px-2 py-1"
                              readonly>{{ $requisicion->descripcion }}</textarea>

                    <button type="button"
                            onclick="confirmarRecepcion()"
                            class="mt-4 bg-primary text-white w-full py-2 rounded font-semibold">
                        Registrar Recepción
                    </button>

                </div>
            </div>

            <!-- DERECHA -->
            <div class="lg:col-span-7">
                <div class="bg-white border-2 border-primary rounded-xl p-4">

                    <label class="font-semibold">Detalle de Productos</label>

                    <table class="w-full border mt-2">
                        <thead class="bg-primary text-white">
                        <tr>
                            <th>Cod</th>
                            <th>Producto</th>
                            <th>Cant</th>
                            <th>C/U</th>
                            <th>Subtotal</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($requisicion->detalles as $d)
                            @php
                                $cantidadRecibida = $d->cantidad_recibida ?? 0;
                                $cantidadPendiente = $d->cantidad - $cantidadRecibida;
                            @endphp

                            <tr data-precio="{{ $d->precio_unitario }}">
                                <td class="border p-1 text-center">{{ $d->id_producto }}</td>

                                <td class="border p-1">
                                    {{ $d->producto->nombre }}
                                </td>

                                <td class="border p-1 text-center">
                                    <button type="button"
                                            onclick="editarCantidad(this)"
                                            class="px-3 py-1 rounded bg-primarySoft font-semibold"
                                            data-real="{{ $d->cantidad }}">
                                        {{ $cantidadPendiente }}
                                    </button>

                                    <!-- INPUTS INTERNOS (NO SE MODIFICAN) -->
                                    <input type="hidden"
                                           name="productos[{{ $loop->index }}][id_producto]"
                                           value="{{ $d->id_producto }}">

                                    <input type="hidden"
                                           name="productos[{{ $loop->index }}][cantidad]"
                                           value="{{ $d->cantidad }}">

                                    <input type="hidden"
                                           name="productos[{{ $loop->index }}][precio_unitario]"
                                           value="{{ $d->precio_unitario }}">
                                </td>

                                <td class="border p-1 text-right">
                                    Q {{ number_format($d->precio_unitario,2) }}
                                </td>

                                <td class="border p-1 text-right subtotal">
                                    Q {{ number_format($d->subtotal,2) }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="text-right mt-3 font-bold">
                        TOTAL: Q
                        <span id="totalGeneral">
                            {{ number_format($requisicion->detalles->sum('subtotal'),2) }}
                        </span>
                    </div>

                </div>
            </div>

        </div>
    </div>
</form>

<script>
    const colorPrincipal = '#6fae2d';

    $(document).ready(function () {
        $('#proveedor').select2({
            placeholder: 'Buscar proveedor...',
            width: '100%'
        });
    });

    function confirmarRecepcion() {
        Swal.fire({
            title: '¿Confirmar recepción?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: colorPrincipal
        }).then(r => {
            if (r.isConfirmed) solicitarDatosFactura();
        });
    }

    function solicitarDatosFactura() {
        Swal.fire({
            title: 'Datos de Recepción',
            html: `
                <input id="serie" class="swal2-input" placeholder="Serie factura">
                <input id="numero" class="swal2-input" placeholder="Número factura">
                <select id="pago" class="swal2-input">
                    <option value="">Tipo de pago</option>
                    <option value="contado">Contado</option>
                    <option value="credito">Crédito</option>
                    <option value="parcial">Parcial</option>
                </select>
                <input id="documento" class="swal2-input" placeholder="Documento recepción">
            `,
            confirmButtonColor: colorPrincipal,
            showCancelButton: true,
            preConfirm: () => {
                if (!numero.value || !pago.value || !documento.value) {
                    Swal.showValidationMessage('Complete todos los campos');
                    return false;
                }
                return {
                    serie: serie.value,
                    numero: numero.value,
                    pago: pago.value,
                    documento: documento.value
                };
            }
        }).then(r => {
            if (r.isConfirmed) {
                serie_factura.value = r.value.serie;
                numero_factura.value = r.value.numero;
                forma_pago.value = r.value.pago;
                numero_documento.value = r.value.documento;
                document.getElementById('formRecepcion').submit();
            }
        });
    }

    /* ================== FUNCIONES EXISTENTES (SIN CAMBIOS) ================== */

    function editarCantidad(btn) {
        const fila = btn.closest('tr');
        const inputCantidad = fila.querySelector('input[name$="[cantidad]"]');
        const precio = parseFloat(fila.dataset.precio);

        Swal.fire({
            title: 'Modificar cantidad',
            input: 'number',
            inputValue: inputCantidad.value,
            inputAttributes: { min: 0, step: 1 },
            showCancelButton: true,
            confirmButtonColor: colorPrincipal,
            preConfirm: (value) => {
                if (value === '' || value < 0) {
                    Swal.showValidationMessage('Cantidad inválida');
                    return false;
                }
                return value;
            }
        }).then(result => {
            if (!result.isConfirmed) return;

            const nuevaCantidad = parseInt(result.value);
            btn.textContent = nuevaCantidad;
            inputCantidad.value = nuevaCantidad;

            const subtotal = nuevaCantidad * precio;
            fila.querySelector('.subtotal').textContent = 'Q ' + subtotal.toFixed(2);

            recalcularTotal();
        });
    }

    function recalcularTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(el => {
            total += parseFloat(el.textContent.replace('Q',''));
        });
        document.getElementById('totalGeneral').textContent = total.toFixed(2);
    }
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Recepción registrada',
        text: '{{ session('success') }}',
        confirmButtonColor: '#6fae2d'
    });
</script>
@endif

@endsection
