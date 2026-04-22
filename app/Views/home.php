<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | FRUVER</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f0f2f5; }

        /* ── NAVBAR ─────────────────────────────── */
        .navbar {
            background: #1b5e20;
            color: white;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 56px;
            box-shadow: 0 2px 8px rgba(0,0,0,.2);
            position: sticky; top: 0; z-index: 100;
        }
        .navbar-brand { display: flex; align-items: center; gap: 10px; }
        .navbar-brand h1 { font-size: 1.3rem; letter-spacing: 1px; }
        .navbar-brand span { font-size: 1.5rem; }
        .navbar a {
            color: rgba(255,255,255,.85);
            text-decoration: none;
            margin-left: 4px;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: .88rem;
            transition: background .2s;
        }
        .navbar a:hover { background: rgba(255,255,255,.15); color: white; }
        .navbar a.activo { background: rgba(255,255,255,.2); color: white; }

        /* ── LAYOUT ─────────────────────────────── */
        .container { max-width: 1280px; margin: 0 auto; padding: 24px 20px; }

        .bienvenida {
            margin-bottom: 24px;
        }
        .bienvenida h2 { font-size: 1.4rem; color: #1b5e20; }
        .bienvenida p  { color: #777; font-size: .9rem; margin-top: 4px; }

        /* ── KPI CARDS ──────────────────────────── */
        .kpis {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }
        .kpi {
            background: white;
            border-radius: 10px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 1px 4px rgba(0,0,0,.08);
            border-left: 4px solid #2e7d32;
        }
        .kpi-icon {
            font-size: 2rem;
            width: 52px; height: 52px;
            border-radius: 50%;
            background: #e8f5e9;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .kpi-valor { font-size: 1.8rem; font-weight: bold; color: #333; line-height: 1; }
        .kpi-label { font-size: .8rem; color: #777; margin-top: 4px; }

        /* ── GRID PRINCIPAL ─────────────────────── */
        .grid-main {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .grid-bottom {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        /* ── CARDS ──────────────────────────────── */
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 1px 4px rgba(0,0,0,.08);
            overflow: hidden;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f0;
        }
        .card-header h3 { font-size: 1rem; color: #333; }
        .card-body { padding: 16px 20px; }
        .ver-todo {
            font-size: .82rem; color: #2e7d32;
            text-decoration: none; font-weight: bold;
        }
        .ver-todo:hover { text-decoration: underline; }

        /* ── TABLA COMPACTA ─────────────────────── */
        .tabla-dash { width: 100%; border-collapse: collapse; }
        .tabla-dash th {
            font-size: .78rem; color: #999;
            text-align: left; padding: 6px 10px;
            border-bottom: 1px solid #f0f0f0;
            text-transform: uppercase; letter-spacing: .5px;
        }
        .tabla-dash td {
            padding: 10px 10px;
            border-bottom: 1px solid #f9f9f9;
            font-size: .88rem; vertical-align: middle;
        }
        .tabla-dash tr:last-child td { border-bottom: none; }
        .tabla-dash tr:hover td { background: #fafafa; }

        /* ── BADGES ─────────────────────────────── */
        .badge {
            padding: 3px 10px; border-radius: 20px;
            font-size: .75rem; font-weight: bold; color: white;
            display: inline-block;
        }

        /* ── ALERTAS ────────────────────────────── */
        .alerta-item {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 0; border-bottom: 1px solid #f9f9f9;
            font-size: .88rem;
        }
        .alerta-item:last-child { border-bottom: none; }
        .alerta-icon {
            width: 34px; height: 34px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; flex-shrink: 0;
        }
        .alerta-rojo  { background: #ffebee; }
        .alerta-naranja { background: #fff3e0; }
        .alerta-info    { font-size: .82rem; color: #555; }
        .alerta-sub     { font-size: .78rem; color: #999; }

        /* ── ESTADOS CHART ──────────────────────── */
        .estados-list { padding: 4px 0; }
        .estado-row {
            display: flex; align-items: center;
            gap: 10px; padding: 8px 0;
            border-bottom: 1px solid #f9f9f9;
        }
        .estado-row:last-child { border-bottom: none; }
        .estado-nombre { font-size: .88rem; color: #555; min-width: 140px; }
        .estado-barra-cont {
            flex: 1; background: #f0f0f0;
            border-radius: 10px; height: 8px; overflow: hidden;
        }
        .estado-barra-fill { height: 8px; border-radius: 10px; }
        .estado-num {
            font-size: .85rem; font-weight: bold;
            color: #333; min-width: 24px; text-align: right;
        }

        /* ── EMPTY STATE ────────────────────────── */
        .empty {
            text-align: center; color: #bbb;
            padding: 24px; font-size: .9rem;
        }

        /* ── ALERTA FLASH ───────────────────────── */
        .flash {
            padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;
            background: #e8f5e9; color: #2e7d32; border-left: 4px solid #2e7d32;
            font-size: .9rem;
        }
    </style>
</head>
<body>

<?= view('partials/navbar') ?>

<div class="container">

    <?php if (session()->getFlashdata('mensaje')): ?>
        <div class="flash"><?= session()->getFlashdata('mensaje') ?></div>
    <?php endif; ?>

    <div class="bienvenida">
        <h2>👋 Bienvenido al Panel FRUVER</h2>
        <p>
            <?= date('l, d \d\e F \d\e Y') ?> —
            Resumen general del sistema
        </p>
    </div>

    <!-- ── KPIs ──────────────────────────────────── -->
    <div class="kpis">
        <div class="kpi">
            <div class="kpi-icon">📋</div>
            <div>
                <div class="kpi-valor"><?= $totalPedidos ?></div>
                <div class="kpi-label">Pedidos totales</div>
            </div>
        </div>
        <div class="kpi" style="border-color:#1565c0">
            <div class="kpi-icon" style="background:#e3f2fd">👥</div>
            <div>
                <div class="kpi-valor" style="color:#1565c0"><?= $totalClientes ?></div>
                <div class="kpi-label">Clientes registrados</div>
            </div>
        </div>
        <div class="kpi" style="border-color:#e65100">
            <div class="kpi-icon" style="background:#fff3e0">📦</div>
            <div>
                <div class="kpi-valor" style="color:#e65100"><?= $totalProductos ?></div>
                <div class="kpi-label">Productos en catálogo</div>
            </div>
        </div>
        <div class="kpi" style="border-color:#c62828">
            <div class="kpi-icon" style="background:#ffebee">⚠️</div>
            <div>
                <div class="kpi-valor" style="color:#c62828">
                    <?= count($stockBajo) + count($vencidas) ?>
                </div>
                <div class="kpi-label">Alertas activas</div>
            </div>
        </div>
    </div>

    <!-- ── FILA PRINCIPAL ────────────────────────── -->
    <div class="grid-main">

        <!-- Pedidos recientes -->
        <div class="card">
            <div class="card-header">
                <h3>📋 Pedidos Recientes</h3>
                <a href="<?= base_url('pedidos') ?>" class="ver-todo">Ver todos →</a>
            </div>
            <div class="card-body" style="padding:0">
                <?php if (empty($pedidosRecientes)): ?>
                    <p class="empty">Sin pedidos registrados aún.</p>
                <?php else: ?>
                <table class="tabla-dash">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($pedidosRecientes as $p):
                        $st    = $p['status_actual'] ?? 'pedido';
                        $color = $colores[$st] ?? '#999';
                    ?>
                    <tr>
                        <td><strong>#<?= esc($p['id_pedido']) ?></strong></td>
                        <td><?= esc($p['nombre']) ?> <?= esc($p['ap_paterno']) ?></td>
                        <td><?= esc($p['fecha']) ?></td>
                        <td>
                            <span class="badge" style="background:<?= $color ?>">
                                <?= esc($estados[$st] ?? $st) ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?= base_url('pedidos/ver/' . $p['id_pedido']) ?>"
                                style="color:#1565c0;font-size:.82rem;text-decoration:none">
                                👁️ Ver
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>

        <!-- Distribución de estados -->
        <div class="card">
            <div class="card-header">
                <h3>📊 Estados de Pedidos</h3>
            </div>
            <div class="card-body">
                <div class="estados-list">
                <?php
                $maxEstado = max(array_values($porEstado) ?: [1]);
                foreach ($estados as $key => $label):
                    $count = $porEstado[$key] ?? 0;
                    $pct   = $maxEstado > 0 ? ($count / $maxEstado) * 100 : 0;
                    $color = $colores[$key] ?? '#999';
                ?>
                <div class="estado-row">
                    <span class="estado-nombre"><?= $label ?></span>
                    <div class="estado-barra-cont">
                        <div class="estado-barra-fill"
                            style="width:<?= $pct ?>%;background:<?= $color ?>">
                        </div>
                    </div>
                    <span class="estado-num"><?= $count ?></span>
                </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ── FILA INFERIOR ─────────────────────────── -->
    <div class="grid-bottom">

        <!-- Alertas de stock bajo -->
        <div class="card">
            <div class="card-header">
                <h3>📉 Stock Bajo</h3>
                <a href="<?= base_url('inventario/existencias') ?>" class="ver-todo">
                    Ver inventario →
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($stockBajo)): ?>
                    <p class="empty">✅ Todo el stock está en niveles normales.</p>
                <?php else: ?>
                    <?php foreach ($stockBajo as $ex):
                        $disp = $ex['exis_total_general'] - $ex['exis_bloqueo'];
                    ?>
                    <div class="alerta-item">
                        <div class="alerta-icon alerta-naranja">📉</div>
                        <div>
                            <div class="alerta-info">
                                <strong><?= esc($ex['producto_nombre']) ?></strong>
                            </div>
                            <div class="alerta-sub">
                                Solo <strong style="color:#c62828"><?= $disp ?></strong>
                                <?= esc($ex['unidad_venta']) ?> disponibles
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Alertas de caducidad -->
        <div class="card">
            <div class="card-header">
                <h3>⏰ Alertas de Caducidad</h3>
                <a href="<?= base_url('inventario/entradas') ?>" class="ver-todo">
                    Ver entradas →
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($vencidas) && empty($porVencer)): ?>
                    <p class="empty">✅ Sin productos por vencer.</p>
                <?php else: ?>

                    <?php foreach ($vencidas as $e): ?>
                    <div class="alerta-item">
                        <div class="alerta-icon alerta-rojo">🚨</div>
                        <div>
                            <div class="alerta-info">
                                <strong><?= esc($e['producto_nombre']) ?></strong>
                                — <span style="color:#c62828">VENCIDA</span>
                            </div>
                            <div class="alerta-sub">
                                Caducó el <?= esc($e['fecha_caducidad']) ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <?php foreach ($porVencer as $e):
                        $diff = (new \DateTime())->diff(new \DateTime($e['fecha_caducidad']))->days;
                    ?>
                    <div class="alerta-item">
                        <div class="alerta-icon alerta-naranja">⚠️</div>
                        <div>
                            <div class="alerta-info">
                                <strong><?= esc($e['producto_nombre']) ?></strong>
                            </div>
                            <div class="alerta-sub">
                                Vence en <strong style="color:#f57c00">
                                    <?= $diff ?> día(s)
                                </strong>
                                (<?= esc($e['fecha_caducidad']) ?>)
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>

                <?php endif; ?>
            </div>
        </div>

    </div><!-- /grid-bottom -->

</div><!-- /container -->

</body>
</html>