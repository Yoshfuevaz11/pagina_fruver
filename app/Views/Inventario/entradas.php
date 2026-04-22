<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Entradas | FRUVER</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .navbar { background: #2e7d32; color: white; padding: 12px 24px;
            display: flex; align-items: center; justify-content: space-between; }
        .navbar h1 { font-size: 1.3rem; }
        .navbar a { color: white; text-decoration: none; margin-left: 16px; font-size: .9rem; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 16px; }
        .tabs { display: flex; gap: 4px; margin-bottom: 0; }
        .tab { padding: 10px 20px; border-radius: 8px 8px 0 0; text-decoration: none;
            font-size: .9rem; background: #ddd; color: #555; }
        .tab.activo { background: white; color: #2e7d32; font-weight: bold; }
        .card { background: white; border-radius: 0 8px 8px 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,.1); padding: 24px; }
        .card-header { display: flex; justify-content: space-between;
            align-items: center; margin-bottom: 20px; }
        .btn { padding: 8px 16px; border: none; border-radius: 5px;
            cursor: pointer; font-size: .85rem; text-decoration: none; display: inline-block; }
        .btn-verde { background: #2e7d32; color: white; }
        .btn-azul  { background: #1565c0; color: white; }
        .btn-rojo  { background: #c62828; color: white; }
        .btn:hover { opacity: .85; }
        .alerta { padding: 12px 16px; border-radius: 5px; margin-bottom: 16px;
            background: #e8f5e9; color: #2e7d32; border-left: 4px solid #2e7d32; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f9f9f9; text-align: left; padding: 10px 12px;
            border-bottom: 2px solid #ddd; font-size: .82rem; color: #555; }
        td { padding: 9px 12px; border-bottom: 1px solid #eee; font-size: .88rem; vertical-align: middle; }
        tr:hover td { background: #fafafa; }
        .acciones { display: flex; gap: 6px; }
        .badge { padding: 3px 10px; border-radius: 20px; font-size: .78rem; font-weight: bold; }
        .badge-frutas   { background: #fff3e0; color: #e65100; }
        .badge-verduras { background: #e8f5e9; color: #2e7d32; }
        .badge-hiervas  { background: #f3e5f5; color: #6a1b9a; }
        .caducidad-ok      { color: #2e7d32; }
        .caducidad-pronto  { color: #f57c00; font-weight: bold; }
        .caducidad-vencida { color: #c62828; font-weight: bold; }
    </style>
</head>
<body>

<?= view('partials/navbar') ?>>

<div class="container">
    <?php if (session()->getFlashdata('mensaje')): ?>
        <div class="alerta"><?= session()->getFlashdata('mensaje') ?></div>
    <?php endif; ?>

    <!-- Tabs de navegación del inventario -->
    <div class="tabs">
        <a href="<?= base_url('inventario/entradas') ?>" class="tab activo"> Entradas</a>
        <a href="<?= base_url('inventario/existencias') ?>" class="tab"> Existencias</a>
        <a href="<?= base_url('inventario/mermas') ?>" class="tab"> Mermas</a>
    </div>

    <div class="card">
        <div class="card-header">
            <h2> <?= esc($titulo) ?></h2>
            <a href="<?= base_url('inventario/entradas/crear') ?>" class="btn btn-verde">
                + Nueva Entrada
            </a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Producto</th>
                    <th>Fecha entrada</th>
                    <th>Caducidad</th>
                    <th>Cantidad compra</th>
                    <th>Precio compra</th>
                    <th>Conversión venta</th>
                    <th>Unidad venta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($entradas)): ?>
                <tr><td colspan="9" style="text-align:center;color:#999;padding:30px;">
                    Sin entradas registradas.
                </td></tr>
            <?php else: ?>
                <?php
                $hoy = new DateTime();
                foreach ($entradas as $e):
                    $caducidad = new DateTime($e['fecha_caducidad']);
                    $diff = $hoy->diff($caducidad)->days;
                    $vencida = $caducidad < $hoy;
                    $classCad = $vencida ? 'caducidad-vencida'
                        : ($diff <= 3 ? 'caducidad-pronto' : 'caducidad-ok');
                ?>
                <tr>
                    <td><?= esc($e['id_entrada']) ?></td>
                    <td>
                        <strong><?= esc($e['producto_nombre']) ?></strong><br>
                        <span class="badge badge-<?= esc($e['categoria']) ?>">
                            <?= esc($e['categoria']) ?>
                        </span>
                    </td>
                    <td><?= esc($e['fecha']) ?></td>
                    <td class="<?= $classCad ?>">
                        <?= esc($e['fecha_caducidad']) ?>
                        <?= $vencida ? 'VENCIDA' : ($diff <= 3 ? " (en {$diff} días)" : '') ?>
                    </td>
                    <td>
                        <?= esc($e['cantidad']) ?>
                        <small><?= esc($e['unidad_compra']) ?></small>
                    </td>
                    <td>$<?= number_format($e['precio_compra'], 2) ?></td>
                    <td><strong><?= esc($e['convercion']) ?></strong></td>
                    <td><?= esc($e['unidad_venta']) ?></td>
                    <td>
                        <div class="acciones">
                            <a href="<?= base_url('inventario/entradas/editar/' . $e['id_entrada']) ?>"
                                class="btn btn-azul"></a>
                            <a href="<?= base_url('inventario/entradas/eliminar/' . $e['id_entrada']) ?>"
                                class="btn btn-rojo"
                                onclick="return confirm('¿Eliminar esta entrada?')"></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>