<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedido #<?= esc($pedido['id_pedido']) ?> | FRUVER</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .navbar { background: #2e7d32; color: white; padding: 12px 24px;
            display: flex; align-items: center; justify-content: space-between; }
        .navbar h1 { font-size: 1.3rem; }
        .navbar a { color: white; text-decoration: none; margin-left: 16px; }
        .container { max-width: 950px; margin: 30px auto; padding: 0 16px; }
        .grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }
        .card { background: white; border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,.1); padding: 24px; margin-bottom: 20px; }
        .card h3 { color: #333; margin-bottom: 16px; font-size: 1rem;
            padding-bottom: 8px; border-bottom: 2px solid #e8f5e9; }
        .info-row { display: flex; justify-content: space-between;
            padding: 6px 0; border-bottom: 1px solid #f5f5f5; font-size: .9rem; }
        .info-row span:first-child { color: #777; }
        .info-row span:last-child  { font-weight: bold; color: #333; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f9f9f9; padding: 8px 12px; text-align: left;
            font-size: .82rem; color: #555; border-bottom: 2px solid #ddd; }
        td { padding: 8px 12px; border-bottom: 1px solid #eee; font-size: .9rem; }
        .badge-status { padding: 5px 14px; border-radius: 20px;
            font-size: .85rem; font-weight: bold; color: white; display: inline-block; }
        .btn { padding: 8px 16px; border: none; border-radius: 5px;
            cursor: pointer; font-size: .85rem; text-decoration: none; display: inline-block; }
        .btn-verde { background: #2e7d32; color: white; }
        .btn-gris  { background: #757575; color: white; }
        .btn:hover { opacity: .85; }
        .total-box { background: #e8f5e9; border-radius: 6px; padding: 14px;
            text-align: right; margin-top: 12px; }
        .total-box strong { font-size: 1.2rem; color: #2e7d32; }
        .alerta { padding: 12px 16px; border-radius: 5px; margin-bottom: 16px;
            background: #e8f5e9; color: #2e7d32; border-left: 4px solid #2e7d32; }
        .historial-item { display: flex; gap: 12px; align-items: center;
            padding: 8px 0; border-bottom: 1px solid #f5f5f5; }
        .hist-dot { width: 10px; height: 10px; border-radius: 50%;
            background: #2e7d32; flex-shrink: 0; }
        .hist-info { font-size: .85rem; }
        .hist-fecha { color: #999; font-size: .78rem; }
        .ruta { color: #999; font-size: .83rem; margin-bottom: 16px; }
        .ruta a { color: #2e7d32; text-decoration: none; }
        select { padding: 8px 12px; border: 1px solid #ddd;
            border-radius: 5px; font-size: .9rem; width: 100%; }
    </style>
</head>
<body>

<?= view('partials/navbar') ?>

<div class="container">
    <?php if (session()->getFlashdata('mensaje')): ?>
        <div class="alerta"><?= session()->getFlashdata('mensaje') ?></div>
    <?php endif; ?>

    <p class="ruta"><a href="<?= base_url('pedidos') ?>">← Volver a todos los pedidos</a></p>

    <div class="grid">
        <!-- COLUMNA IZQUIERDA -->
        <div>
            <!-- Info del pedido -->
            <div class="card">
                <h3> Pedido #<?= esc($pedido['id_pedido']) ?></h3>

                <?php
                $st    = $pedido['status_actual'] ?? 'pedido';
                $color = $colores[$st] ?? '#999';
                ?>
                <div style="margin-bottom:16px">
                    <span class="badge-status" style="background:<?= $color ?>">
                        <?= esc($estados[$st] ?? $st) ?>
                    </span>
                </div>

                <div class="info-row">
                    <span>Fecha</span>
                    <span><?= esc($pedido['fecha']) ?></span>
                </div>
                <div class="info-row">
                    <span>Cliente</span>
                    <span><?= esc($pedido['nombre']) ?> <?= esc($pedido['ap_paterno']) ?></span>
                </div>
                <div class="info-row">
                    <span>Teléfono</span>
                    <span><?= esc($pedido['telefono']) ?></span>
                </div>
                <div class="info-row">
                    <span>Dirección entrega</span>
                    <span>
                        <?php if (!empty($pedido['calle'])): ?>
                            <?= esc($pedido['calle']) ?> #<?= esc($pedido['numero']) ?>,
                            <?= esc($pedido['colonia']) ?>,
                            <?= esc($pedido['municipio']) ?>
                        <?php else: ?>
                            Sin dirección registrada
                        <?php endif; ?>
                    </span>
                </div>
                <div class="info-row">
                    <span>Repartidor</span>
                    <span><?= esc($pedido['rep_nombre']) ?> <?= esc($pedido['rep_ap']) ?></span>
                </div>
            </div>

            <!-- Productos del pedido -->
            <div class="card">
                <h3>🛒 Productos</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Unidad</th>
                            <th>Precio</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $totalPedido = 0;
                    foreach ($productos as $pp):
                        $totalPedido += $pp['total'];
                    ?>
                    <tr>
                        <td><strong><?= esc($pp['producto_nombre']) ?></strong></td>
                        <td><?= esc($pp['cantidad']) ?></td>
                        <td><?= esc($pp['unidad_venta']) ?></td>
                        <td>$<?= number_format($pp['precio_venta'], 2) ?></td>
                        <td>$<?= number_format($pp['total'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="total-box">
                    Total: <strong>$<?= number_format($totalPedido, 2) ?></strong>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA -->
        <div>
            <!-- Cambiar estado -->
            <div class="card">
                <h3> Cambiar Estado</h3>
                <form action="<?= base_url('pedidos/cambiar-status/' . $pedido['id_pedido']) ?>"
                        method="POST">
                    <?= csrf_field() ?>
                    <div style="margin-bottom:12px">
                        <label style="font-size:.85rem;color:#555;font-weight:bold;display:block;margin-bottom:6px">
                            Nuevo estado:
                        </label>
                        <select name="estado">
                            <?php foreach ($estados as $key => $label): ?>
                            <option value="<?= $key ?>"
                                <?= ($st === $key) ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-verde" style="width:100%">
                        Actualizar Estado
                    </button>
                </form>
            </div>

            <!-- Historial de estados -->
            <div class="card">
                <h3> Historial</h3>
                <?php if (empty($historial)): ?>
                    <p style="color:#999;font-size:.88rem">Sin historial aún.</p>
                <?php else: ?>
                    <?php foreach ($historial as $h): ?>
                    <div class="historial-item">
                        <div class="hist-dot"
                            style="background:<?= $colores[$h['estado']] ?? '#999' ?>">
                        </div>
                        <div class="hist-info">
                            <div><?= esc($estados[$h['estado']] ?? $h['estado']) ?></div>
                            <div class="hist-fecha"><?= esc($h['fecha']) ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>