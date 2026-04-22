<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Pedido | FRUVER</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .navbar { background: #2e7d32; color: white; padding: 12px 24px;
            display: flex; align-items: center; justify-content: space-between; }
        .navbar h1 { font-size: 1.3rem; }
        .navbar a { color: white; text-decoration: none; margin-left: 16px; }
        .container { max-width: 850px; margin: 30px auto; padding: 0 16px; }
        .card { background: white; border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,.1); padding: 30px; }
        .card h2 { margin-bottom: 6px; color: #333; }
        .subtitulo { color: #777; font-size: .85rem; margin-bottom: 24px; }
        .seccion { font-weight: bold; color: #2e7d32; margin: 20px 0 12px;
            padding-bottom: 6px; border-bottom: 2px solid #e8f5e9; }
        .fila { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-size: .85rem; color: #555;
            margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 10px 12px;
            border: 1px solid #ddd; border-radius: 5px; font-size: .9rem; }
        input:focus, select:focus { outline: none; border-color: #2e7d32; }
        /* Tabla de productos */
        .tabla-prods { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .tabla-prods th { background: #f5f5f5; padding: 8px 10px;
            font-size: .82rem; color: #555; text-align: left;
            border-bottom: 2px solid #ddd; }
        .tabla-prods td { padding: 8px 6px; border-bottom: 1px solid #eee; }
        .tabla-prods input, .tabla-prods select {
            padding: 7px 8px; font-size: .88rem; }
        .total-fila { text-align: right; font-weight: bold; color: #2e7d32; }
        .btn { padding: 8px 16px; border: none; border-radius: 5px;
            cursor: pointer; font-size: .85rem; text-decoration: none; display: inline-block; }
        .btn-verde  { background: #2e7d32; color: white; }
        .btn-gris   { background: #757575; color: white; }
        .btn-rojo   { background: #c62828; color: white; padding: 5px 10px; }
        .btn-outline { background: white; color: #2e7d32;
            border: 1px solid #2e7d32; }
        .btn:hover { opacity: .85; }
        .form-footer { display: flex; gap: 12px; margin-top: 24px; }
        .total-general { background: #e8f5e9; border-radius: 6px;
            padding: 14px; text-align: right; font-size: 1rem;
            margin-top: 12px; }
        .total-general strong { color: #2e7d32; font-size: 1.3rem; }
        .ruta { color: #999; font-size: .83rem; margin-bottom: 16px; }
        .ruta a { color: #2e7d32; text-decoration: none; }
    </style>
</head>
<body>

<?= view('partials/navbar') ?>

<div class="container">
    <p class="ruta"><a href="<?= base_url('pedidos') ?>">← Volver a Pedidos</a></p>

    <div class="card">
        <h2> <?= esc($titulo) ?></h2>
        <p class="subtitulo">Registra el pedido con los productos solicitados</p>

        <form action="<?= $accion ?>" method="POST" id="form-pedido">
            <?= csrf_field() ?>

            <div class="seccion"> Datos del pedido</div>

            <div class="fila">
                <div class="form-group">
                    <label>Cliente *</label>
                    <select name="id_cliente1" required>
                        <option value="">-- Selecciona cliente --</option>
                        <?php foreach ($clientes as $c): ?>
                        <option value="<?= $c['id_cliente'] ?>">
                            <?= esc($c['nombre']) ?>
                            <?= esc($c['ap_paterno']) ?>
                            — <?= esc($c['telefono']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Repartidor *</label>
                    <select name="id_repartidor" required>
                        <option value="">-- Selecciona repartidor --</option>
                        <?php foreach ($repartidores as $r): ?>
                        <option value="<?= $r['id_repartidor'] ?>">
                            <?= esc($r['nombre']) ?> <?= esc($r['ap_paterno']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group" style="max-width:220px">
                <label>Fecha del pedido</label>
                <input type="date" name="fecha"
                    value="<?= date('Y-m-d') ?>">
            </div>

            <div class="seccion">🛒 Productos del pedido</div>

            <table class="tabla-prods" id="tabla-prods">
                <thead>
                    <tr>
                        <th style="width:35%">Producto</th>
                        <th style="width:15%">Cantidad</th>
                        <th style="width:15%">Unidad</th>
                        <th style="width:18%">Precio venta</th>
                        <th style="width:12%">Total</th>
                        <th style="width:5%"></th>
                    </tr>
                </thead>
                <tbody id="filas-productos">
                    <!-- Se genera dinámicamente -->
                </tbody>
            </table>

            <div style="margin-top:10px">
                <button type="button" class="btn btn-outline" onclick="agregarFila()">
                    + Agregar producto
                </button>
            </div>

            <div class="total-general">
                Total del pedido: <strong id="total-general">$0.00</strong>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-verde">💾 Guardar Pedido</button>
                <a href="<?= base_url('pedidos') ?>" class="btn btn-gris">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script>
<!-- Reemplaza el bloque de script al final del form.php de pedidos -->
<script>
const colores = <?= json_encode(array_values(['#fff3e0','#e3f2fd','#fff8e1'])) ?>;

// AJAX AUTOCOMPLETE 
// ============================================================
let filaCount = 0;

function agregarFila() {
    const tbody = document.getElementById('filas-productos');
    const i = filaCount++;

    const fila = document.createElement('tr');
    fila.id = `fila-${i}`;
    fila.innerHTML = `
        <td style="position:relative">
            <!-- Input de búsqueda visible -->
            <input type="text" id="busq-${i}" placeholder="Escribe nombre o categoría..."
                autocomplete="off"
                oninput="buscarProducto(${i})"
                onfocus="buscarProducto(${i})"
                style="width:100%;padding:8px;border:1px solid #ddd;border-radius:5px;font-size:.88rem">

            <!-- Campo oculto con el ID real del producto -->
            <input type="hidden" name="id_producto3[]" id="prod-id-${i}">

            <!-- Dropdown de resultados -->
            <div id="drop-${i}" style="
                display:none; position:absolute; top:100%; left:0; right:0;
                background:white; border:1px solid #ddd; border-radius:6px;
                box-shadow:0 4px 12px rgba(0,0,0,.1); z-index:999;
                max-height:220px; overflow-y:auto;">
            </div>
        </td>
        <td>
            <input type="number" name="cantidad[]" id="cant-${i}"
                min="0.01" step="0.01" required placeholder="0"
                oninput="calcularFila(${i})"
                style="width:100%;padding:8px;border:1px solid #ddd;border-radius:5px;font-size:.88rem">
        </td>
        <td>
            <select name="unidad_venta[]" id="unidad-${i}"
                style="width:100%;padding:8px;border:1px solid #ddd;border-radius:5px;font-size:.88rem">
                <option value="kilos">Kilos</option>
                <option value="domos">Domos</option>
                <option value="ramos">Ramos</option>
            </select>
        </td>
        <td>
            <input type="number" name="precio_venta[]" id="precio-${i}"
                min="0" step="0.01" required placeholder="0.00"
                oninput="calcularFila(${i})"
                style="width:100%;padding:8px;border:1px solid #ddd;border-radius:5px;font-size:.88rem">
        </td>
        <td id="total-${i}" style="font-weight:bold;color:#2e7d32;text-align:center">$0.00</td>
        <td style="text-align:center">
            <button type="button"
                style="background:#ffebee;color:#c62828;border:none;border-radius:6px;
                        padding:6px 10px;cursor:pointer;font-size:.9rem"
                onclick="document.getElementById('fila-${i}').remove(); calcularTotal()">
                <i class="ri-delete-bin-line"></i>
            </button>
        </td>
    `;
    tbody.appendChild(fila);

    // Cerrar dropdown al click fuera
    document.addEventListener('click', e => {
        if (!fila.contains(e.target)) {
            document.getElementById(`drop-${i}`).style.display = 'none';
        }
    });
}

// ── AJAX al endpoint que pide la maestra ─────────────────
let timers = {};
function buscarProducto(i) {
    const q    = document.getElementById(`busq-${i}`).value;
    const drop = document.getElementById(`drop-${i}`);

    clearTimeout(timers[i]);
    timers[i] = setTimeout(async () => {
        const url = `<?= base_url('productos/buscar') ?>?q=${encodeURIComponent(q)}`;
        const res  = await fetch(url);
        const data = await res.json();

        drop.innerHTML = '';

        if (data.length === 0) {
            drop.innerHTML = '<div style="padding:12px;color:#aaa;font-size:.85rem;text-align:center">Sin resultados</div>';
            drop.style.display = 'block';
            return;
        }

        data.forEach(prod => {
            const item = document.createElement('div');
            item.style.cssText = 'padding:10px 14px;cursor:pointer;font-size:.88rem;border-bottom:1px solid #f5f5f5;display:flex;justify-content:space-between;align-items:center';
            item.innerHTML = `
                <span>
                    <strong>${prod.nombre}</strong>
                    <small style="color:#aaa;margin-left:6px">${prod.categoria}</small>
                </span>
                <small style="color:#2e7d32;font-weight:600">${prod.unidad_venta}</small>
            `;
            item.addEventListener('mouseenter', () => item.style.background = '#f5fdf5');
            item.addEventListener('mouseleave', () => item.style.background = '');
            item.addEventListener('click', () => seleccionarProducto(i, prod));
            drop.appendChild(item);
        });

        drop.style.display = 'block';
    }, 250); // debounce 250ms
}

function seleccionarProducto(i, prod) {
    document.getElementById(`busq-${i}`).value    = prod.nombre;
    document.getElementById(`prod-id-${i}`).value = prod.id_producto;
    document.getElementById(`unidad-${i}`).value  = prod.unidad_venta;
    document.getElementById(`drop-${i}`).style.display = 'none';
}

function calcularFila(i) {
    const cant   = parseFloat(document.getElementById(`cant-${i}`)?.value)   || 0;
    const precio = parseFloat(document.getElementById(`precio-${i}`)?.value) || 0;
    const total  = cant * precio;
    const el = document.getElementById(`total-${i}`);
    if (el) el.textContent = '$' + total.toFixed(2);
    calcularTotal();
}

function calcularTotal() {
    let suma = 0;
    document.querySelectorAll('[id^="total-"]').forEach(el => {
        suma += parseFloat(el.textContent.replace('$', '')) || 0;
    });
    document.getElementById('total-general').textContent = '$' + suma.toFixed(2);
}

agregarFila(); // Iniciar con una fila
</script>