<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mermas | FRUVER</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .navbar { background: #2e7d32; color: white; padding: 12px 24px;
            display: flex; align-items: center; justify-content: space-between; }
        .navbar h1 { font-size: 1.3rem; }
        .navbar a { color: white; text-decoration: none; margin-left: 16px; font-size: .9rem; }
        .container { max-width: 1100px; margin: 30px auto; padding: 0 16px; }
        .tabs { display: flex; gap: 4px; }
        .tab { padding: 10px 20px; border-radius: 8px 8px 0 0; text-decoration: none;
            font-size: .9rem; background: #ddd; color: #555; }
        .tab.activo { background: white; color: #e65100; font-weight: bold; }
        .card { background: white; border-radius: 0 8px 8px 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,.1); padding: 24px; }
        .card-header { display: flex; justify-content: space-between;
            align-items: center; margin-bottom: 20px; }
        .btn { padding: 8px 16px; border: none; border-radius: 5px;
            cursor: pointer; font-size: .85rem; text-decoration: none; display: inline-block; }
        .btn-naranja { background: #e65100; color: white; }
        .btn-rojo  { background: #c62828; color: white; }
        .btn:hover { opacity: .85; }
        .alerta { padding: 12px 16px; border-radius: 5px; margin-bottom: 16px;
            background: #e8f5e9; color: #2e7d32; border-left: 4px solid #2e7d32; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f9f9f9; text-align: left; padding: 10px 12px;
            border-bottom: 2px solid #ddd; font-size: .82rem; color: #555; }
        td { padding: 10px 12px; border-bottom: 1px solid #eee; font-size: .9rem; vertical-align: middle; }
        tr:hover td { background: #fafafa; }
        .notas { color: #777; font-style: italic; font-size: .85rem; }
    </style>
</head>
<body>

<?= view('partials/navbar') ?>

<div class="container">
    <?php if (session()->getFlashdata('mensaje')): ?>
        <div class="alerta"><?= session()->getFlashdata('mensaje') ?></div>
    <?php endif; ?>

    <div class="tabs">
        <a href="<?= base_url('inventario/entradas') ?>" class="tab"> Entradas</a>
        <a href="<?= base_url('inventario/existencias') ?>" class="tab"> Existencias</a>
        <a href="<?= base_url('inventario/mermas') ?>" class="tab activo"> Mermas</a>
    </div>

    <div class="card">
        <div class="card-header">
            <h2> <?= esc($titulo) ?></h2>
            <a href="<?= base_url('inventario/mermas/crear') ?>" class="btn btn-naranja">
                + Registrar Merma
            </a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Producto</th>
                    <th>Entrada #</th>
                    <th>Cantidad merma</th>
                    <th>Unidad</th>
                    <th>Fecha</th>
                    <th>Observaciones</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($mermas)): ?>
                <tr><td colspan="8" style="text-align:center;color:#999;padding:30px;">
                    Sin mermas registradas.
                </td></tr>
            <?php else: ?>
                <?php foreach ($mermas as $m): ?>
                <tr>
                    <td><?= esc($m['id_merma']) ?></td>
                    <td><strong><?= esc($m['producto_nombre']) ?></strong></td>
                    <td>Entrada #<?= esc($m['id_entrada']) ?>
                        <small style="color:#999">(<?= esc($m['fecha_entrada']) ?>)</small>
                    </td>
                    <td style="color:#c62828; font-weight:bold">
                        <?= esc($m['cantidad']) ?>
                    </td>
                    <td><?= esc($m['unidad_venta']) ?></td>
                    <td><?= esc($m['fecha']) ?></td>
                    <td class="notas"><?= esc($m['notas']) ?></td>
                    <td>
                        <a href="<?= base_url('inventario/mermas/eliminar/' . $m['id_merma']) ?>"
                            class="btn btn-rojo"
                            onclick="return confirm('¿Eliminar esta merma?')"></a>
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