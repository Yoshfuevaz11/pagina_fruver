<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= esc($titulo) ?> | FRUVER</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .navbar { background: #2e7d32; color: white; padding: 12px 24px;
            display: flex; align-items: center; justify-content: space-between; }
        .navbar h1 { font-size: 1.3rem; }
        .navbar a { color: white; text-decoration: none; margin-left: 16px; }
        .container { max-width: 700px; margin: 30px auto; padding: 0 16px; }
        .card { background: white; border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,.1); padding: 30px; }
        .card h2 { color: #333; margin-bottom: 6px; }
        .subtitulo { color: #777; font-size: .85rem; margin-bottom: 24px; }
        .seccion { font-weight: bold; color: #2e7d32; margin: 20px 0 12px;
            padding-bottom: 6px; border-bottom: 2px solid #e8f5e9; }
        .fila { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-size: .85rem; color: #555;
            margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 10px 12px; border: 1px solid #ddd;
            border-radius: 5px; font-size: .95rem; }
        input:focus, select:focus { outline: none; border-color: #2e7d32; }
        .info-box { background: #e8f5e9; border-radius: 6px; padding: 14px;
            margin-top: 16px; font-size: .9rem; }
        .info-box strong { color: #2e7d32; font-size: 1.1rem; }
        .form-footer { display: flex; gap: 12px; margin-top: 24px; }
        .btn { padding: 10px 22px; border: none; border-radius: 5px;
            cursor: pointer; font-size: .9rem; text-decoration: none; display: inline-block; }
        .btn-verde { background: #2e7d32; color: white; }
        .btn-gris  { background: #757575; color: white; }
        .btn:hover { opacity: .85; }
        .errores { background: #ffebee; border-left: 4px solid #c62828;
            padding: 12px 16px; border-radius: 5px; margin-bottom: 20px;
            color: #c62828; font-size: .88rem; }
        .ruta { color: #999; font-size: .83rem; margin-bottom: 16px; }
        .ruta a { color: #2e7d32; text-decoration: none; }
    </style>
</head>
<body>

<?= view('partials/navbar') ?>

<div class="container">
    <p class="ruta"><a href="<?= base_url('inventario/entradas') ?>">← Volver a Entradas</a></p>

    <div class="card">
        <h2><?= esc($titulo) ?></h2>
        <p class="subtitulo">
            La conversión se calcula automáticamente: Cantidad  Equivalente
        </p>

        <?php $errores = session()->getFlashdata('errores'); ?>
        <?php if (!empty($errores)): ?>
            <div class="errores">
                <strong>Corrige los errores:</strong>
                <ul><?php foreach ($errores as $e): ?>
                    <li><?= esc($e) ?></li>
                <?php endforeach; ?></ul>
            </div>
        <?php endif; ?>

        <form action="<?= $accion ?>" method="POST" id="form-entrada">
            <?= csrf_field() ?>

            <div class="seccion"> Producto y fechas</div>

            <div class="form-group">
                <label>Producto *</label>
                <select name="id_producto2" id="sel-producto" required
                    onchange="autoUnidad()">
                    <option value="">-- Selecciona un producto --</option>
                    <?php foreach ($productos as $p): ?>
                    <option value="<?= $p['id_producto'] ?>"
                        data-unidad="<?= $p['unidad_venta'] ?>"
                        <?= (old('id_producto2', $entrada['id_producto2'] ?? '') == $p['id_producto']) ? 'selected' : '' ?>>
                        <?= esc($p['nombre']) ?> (<?= esc($p['categoria']) ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="fila">
                <div class="form-group">
                    <label>Fecha de entrada *</label>
                    <input type="date" name="fecha" id="fecha-entrada" required
                        value="<?= esc(old('fecha', $entrada['fecha'] ?? date('Y-m-d'))) ?>"
                        onchange="sugerirCaducidad()">
                </div>
                <!-- Caducidad oculta, se calcula automáticamente -->
<div class="form-group" style="display:none">
    <label>Fecha de caducidad <small>(automático: +5 días)</small></label>
    <input type="date" name="fecha_caducidad" id="fecha-cad"
        value="<?= esc(old('fecha_caducidad', $entrada['fecha_caducidad'] ?? '')) ?>">
</div>

<!-- Mostrar la fecha calculada solo como info -->
<div style="background:#e8f5e9;border-radius:8px;padding:12px 16px;margin-top:8px;font-size:.88rem">
    Caducidad estimada: <strong id="info-cad" style="color:#2e7d32">—</strong>
    (5 días después de la entrada)
</div>
            </div>

            <div class="seccion"> Cantidades y precio</div>

            <div class="fila">
                <div class="form-group">
                    <label>Unidad de compra *</label>
                    <select name="unidad_compra" required>
                        <?php foreach (['caja','mazo','arpilla'] as $u): ?>
                        <option value="<?= $u ?>"
                            <?= (old('unidad_compra', $entrada['unidad_compra'] ?? '') === $u) ? 'selected' : '' ?>>
                            <?= ucfirst($u) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Unidad de venta *</label>
                    <select name="unidad_venta" id="sel-unidad-venta" required>
                        <?php foreach (['kilos','domos','ramos','pieza'] as $u): ?>
                        <option value="<?= $u ?>"
                            <?= (old('unidad_venta', $entrada['unidad_venta'] ?? '') === $u) ? 'selected' : '' ?>>
                            <?= ucfirst($u) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="fila">
                <div class="form-group">
                    <label>Cantidad comprada *</label>
                    <input type="number" name="cantidad" id="inp-cantidad"
                        required min="0.01" step="0.01"
                        value="<?= esc(old('cantidad', $entrada['cantidad'] ?? '')) ?>"
                        oninput="calcularConvercion()">
                </div>
                <div class="form-group">
                    <label>Precio de compra ($) *</label>
                    <input type="number" name="precio_compra"
                        required min="0.01" step="0.01"
                        value="<?= esc(old('precio_compra', $entrada['precio_compra'] ?? '')) ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Equivalente (unidades de venta por unidad de compra) *</label>
                <input type="number" name="equivalente" id="inp-equivalente"
                    required min="0.01" step="0.01"
                    placeholder="Ej: 10 (1 caja = 10 kilos)"
                    value="<?= esc(old('equivalente', $entrada['equivalente'] ?? '')) ?>"
                    oninput="calcularConvercion()">
            </div>

            <!-- Resultado de conversión -->
            <div class="info-box">
                Conversión total (unidades para venta):
                <strong id="resultado-conv">
                    <?= esc($entrada['convercion'] ?? '0') ?>
                </strong>
                <input type="hidden" name="convercion" id="inp-convercion"
                    value="<?= esc(old('convercion', $entrada['convercion'] ?? 0)) ?>">
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-verde"> Guardar Entrada</button>
                <a href="<?= base_url('inventario/entradas') ?>" class="btn btn-gris">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script>
// ── Auto-fecha caducidad: siempre 5 días después ────────
function sugerirCaducidad() {
    const fechaVal = document.getElementById('fecha-entrada').value;
    if (!fechaVal) return;
    const d = new Date(fechaVal + 'T00:00:00');
    d.setDate(d.getDate() + 5);
    const iso = d.toISOString().split('T')[0];
    document.getElementById('fecha-cad').value = iso;
    // Mostrar en formato legible
    const legible = d.toLocaleDateString('es-MX', {day:'2-digit',month:'long',year:'numeric'});
    const info = document.getElementById('info-cad');
    if (info) info.textContent = legible;
}

// ── Conversión automática ───────────────────────────────
function calcularConvercion() {
    const cant  = parseFloat(document.getElementById('inp-cantidad').value)    || 0;
    const equiv = parseFloat(document.getElementById('inp-equivalente').value) || 0;
    const total = (cant * equiv).toFixed(2);
    document.getElementById('resultado-conv').textContent = total;
    document.getElementById('inp-convercion').value       = total;
}

// ── Auto unidad de venta según producto ────────────────
function autoUnidad() {
    const sel   = document.getElementById('sel-producto');
    const opt   = sel.options[sel.selectedIndex];
    const unidad = opt.dataset.unidad;
    if (unidad) {
        document.getElementById('sel-unidad-venta').value = unidad;
    }
}

// ── Al cargar: si no hay fecha caducidad, calcularla ───
window.onload = () => {
    calcularConvercion();
    // Solo auto-calcular si el campo está vacío (no edición)
    const cad = document.getElementById('fecha-cad');
    if (!cad.value) sugerirCaducidad();
};
</script>
</body>
</html>