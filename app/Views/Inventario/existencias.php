<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Existencias | FRUVER</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .navbar { background: #2e7d32; color: white; padding: 12px 24px;
            display: flex; align-items: center; justify-content: space-between; }
        .navbar h1 { font-size: 1.3rem; }
        .navbar a { color: white; text-decoration: none; margin-left: 16px; font-size: .9rem; }
        .container { max-width: 1100px; margin: 30px auto; padding: 0 16px; }
        .tabs { display: flex; gap: 4px; margin-bottom: 0; }
        .tab { padding: 10px 20px; border-radius: 8px 8px 0 0; text-decoration: none;
            font-size: .9rem; background: #ddd; color: #555; }
        .tab.activo { background: white; color: #2e7d32; font-weight: bold; }
        .card { background: white; border-radius: 0 8px 8px 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,.1); padding: 24px; }
        .card-header { display: flex; justify-content: space-between;
            align-items: center; margin-bottom: 20px; }
        .kpis { display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 16px; margin-bottom: 24px; }
        .kpi { background: #f9f9f9; border-radius: 8px; padding: 16px;
            text-align: center; border-left: 4px solid #2e7d32; }
        .kpi-valor { font-size: 1.8rem; font-weight: bold; color: #2e7d32; }
        .kpi-label { font-size: .82rem; color: #777; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f9f9f9; text-align: left; padding: 10px 12px;
            border-bottom: 2px solid #ddd; font-size: .82rem; color: #555; }
        td { padding: 10px 12px; border-bottom: 1px solid #eee; font-size: .9rem; }
        tr:hover td { background: #fafafa; }
        .badge { padding: 3px 10px; border-radius: 20px; font-size: .78rem; font-weight: bold; }
        .badge-frutas   { background: #fff3e0; color: #e65100; }
        .badge-verduras { background: #e8f5e9; color: #2e7d32; }
        .badge-hiervas  { background: #f3e5f5; color: #6a1b9a; }
        .stock-bajo  { color: #c62828; font-weight: bold; }
        .stock-medio { color: #f57c00; font-weight: bold; }
        .stock-ok    { color: #2e7d32; font-weight: bold; }
        .barra-cont { background: #eee; border-radius: 10px; height: 8px; width: 120px; }
        .barra-fill { height: 8px; border-radius: 10px; background: #2e7d32; }
    </style>
</head>
<body>

<?= view('partials/navbar') ?>

<div class="container">
    <div class="tabs">
        <a href="<?= base_url('inventario/entradas') ?>" class="tab"> Entradas</a>
        <a href="<?= base_url('inventario/existencias') ?>" class="tab activo"> Existencias</a>
        <a href="<?= base_url('inventario/mermas') ?>" class="tab"> Mermas</a>
    </div>

    <div class="card">
        <div class="card-header">
            <h2> <?= esc($titulo) ?></h2>
        </div>

        <!-- KPIs resumen -->
        <?php
        $totalGeneral = array_sum(array_column($existencias, 'exis_total_general'));
        $totalBloq    = array_sum(array_column($existencias, 'exis_bloqueo'));
        $totalVenta   = $totalGeneral - $totalBloq;
        ?>
        <div class="kpis">
            <div class="kpi">
                <div class="kpi-valor"><?= number_format($totalGeneral) ?></div>
                <div class="kpi-label">Total en inventario</div>
            </div>
            <div class="kpi" style="border-color:#c62828">
                <div class="kpi-valor" style="color:#c62828"><?= number_format($totalBloq) ?></div>
                <div class="kpi-label">Bloqueadas</div>
            </div>
            <div class="kpi" style="border-color:#1565c0">
                <div class="kpi-valor" style="color:#1565c0"><?= number_format($totalVenta) ?></div>
                <div class="kpi-label">Disponibles para venta</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Unidad</th>
                    <th>Total general</th>
                    <th>Bloqueadas</th>
                    <th>Para venta</th>
                    <th>Stock del día</th>
                    <th>Nivel</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($existencias as $ex):
                $parVenta = $ex['exis_total_general'] - $ex['exis_bloqueo'];
                $pct = $ex['exis_total_general'] > 0
                    ? min(100, ($parVenta / $ex['exis_total_general']) * 100)
                    : 0;
                $clase = $parVenta <= 10 ? 'stock-bajo'
                    : ($parVenta <= 30 ? 'stock-medio' : 'stock-ok');
            ?>
            <tr>
                <td><strong><?= esc($ex['producto_nombre']) ?></strong></td>
                <td>
                    <span class="badge badge-<?= esc($ex['categoria']) ?>">
                        <?= esc($ex['categoria']) ?>
                    </span>
                </td>
                <td><?= esc($ex['unidad_venta']) ?></td>
                <td><?= esc($ex['exis_total_general']) ?></td>
                <td style="color:#c62828"><?= esc($ex['exis_bloqueo']) ?></td>
                <td class="<?= $clase ?>"><?= $parVenta ?></td>
                <td><?= esc($ex['exis_total_dia']) ?></td>
                <td>
                    <div class="barra-cont">
                        <div class="barra-fill" style="width:<?= $pct ?>%;
                            background: <?= $parVenta <= 10 ? '#c62828' : ($parVenta <= 30 ? '#f57c00' : '#2e7d32') ?>">
                        </div>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>