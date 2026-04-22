<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Control de Pedidos | FRUVER</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f2f5; }

        .navbar {
            background: #1b5e20; color: white; padding: 0 28px;
            display: flex; align-items: center; justify-content: space-between;
            height: 58px; box-shadow: 0 2px 10px rgba(0,0,0,.2);
            position: sticky; top: 0; z-index: 100;
        }
        .navbar-brand { display: flex; align-items: center; gap: 10px; font-size: 1.2rem; font-weight: 700; }
        .navbar a { color: rgba(255,255,255,.85); text-decoration: none;
            margin-left: 4px; padding: 6px 14px; border-radius: 6px;
            font-size: .88rem; transition: background .2s; }
        .navbar a:hover, .navbar a.activo { background: rgba(255,255,255,.18); color: white; }

        .container { max-width: 1280px; margin: 0 auto; padding: 28px 20px; }

        /* KPIs */
        .kpis { display: grid; grid-template-columns: repeat(5, 1fr); gap: 14px; margin-bottom: 24px; }
        .kpi {
            background: white; border-radius: 10px; padding: 18px 20px;
            display: flex; align-items: center; gap: 14px;
            box-shadow: 0 1px 4px rgba(0,0,0,.07);
            border-left: 4px solid #2e7d32; cursor: pointer;
            transition: transform .15s, box-shadow .15s;
        }
        .kpi:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.1); }
        .kpi-icon { font-size: 1.5rem; width: 46px; height: 46px; border-radius: 10px;
            background: #e8f5e9; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .kpi-valor { font-size: 1.7rem; font-weight: 700; color: #1a1a1a; line-height: 1; }
        .kpi-label { font-size: .75rem; color: #888; margin-top: 3px; text-transform: uppercase; letter-spacing: .4px; }

        /* Toolbar */
        .toolbar {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 16px; gap: 12px; flex-wrap: wrap;
        }
        .toolbar-left { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
        .search-box {
            display: flex; align-items: center; gap: 8px;
            background: white; border: 1px solid #ddd; border-radius: 8px;
            padding: 8px 14px; width: 280px; box-shadow: 0 1px 3px rgba(0,0,0,.05);
        }
        .search-box i { color: #aaa; font-size: 1rem; }
        .search-box input { border: none; outline: none; font-size: .9rem; width: 100%; }

        /* Filtros por estado */
        .filtros { display: flex; gap: 6px; flex-wrap: wrap; }
        .filtro-chip {
            padding: 5px 14px; border-radius: 20px; border: 1.5px solid #ddd;
            background: white; cursor: pointer; font-size: .8rem; font-weight: 600;
            transition: all .2s; color: #555;
        }
        .filtro-chip.activo { color: white; border-color: transparent; }
        .filtro-chip:hover { transform: translateY(-1px); }

        /* Botón principal */
        .btn-primary {
            background: #2e7d32; color: white; border: none;
            padding: 10px 20px; border-radius: 8px; cursor: pointer;
            font-size: .88rem; font-weight: 600; text-decoration: none;
            display: inline-flex; align-items: center; gap: 6px;
            transition: background .2s, transform .15s;
        }
        .btn-primary:hover { background: #1b5e20; transform: translateY(-1px); }

        /* Tabla */
        .card {
            background: white; border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,.07); overflow: hidden;
        }
        .card-header {
            padding: 18px 24px; border-bottom: 1px solid #f0f0f0;
            display: flex; justify-content: space-between; align-items: center;
        }
        .card-header h2 { font-size: 1rem; color: #333; font-weight: 600; }

        table { width: 100%; border-collapse: collapse; }
        thead th {
            padding: 12px 16px; text-align: left;
            background: #fafafa; font-size: .78rem;
            color: #999; text-transform: uppercase; letter-spacing: .5px;
            border-bottom: 1px solid #f0f0f0; font-weight: 600;
        }
        tbody td {
            padding: 14px 16px; border-bottom: 1px solid #f7f7f7;
            font-size: .88rem; vertical-align: middle; color: #333;
        }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #fafffe; }

        /* Badge de estado */
        .badge {
            padding: 4px 12px; border-radius: 20px;
            font-size: .75rem; font-weight: 700;
            color: white; display: inline-block; white-space: nowrap;
        }

        /* Acciones */
        .btn-icon {
            width: 32px; height: 32px; border-radius: 7px; border: none;
            cursor: pointer; display: inline-flex; align-items: center;
            justify-content: center; font-size: 1rem; text-decoration: none;
            transition: background .2s;
        }
        .btn-ver    { background: #e3f2fd; color: #1565c0; }
        .btn-del    { background: #ffebee; color: #c62828; }
        .btn-ver:hover { background: #bbdefb; }
        .btn-del:hover { background: #ffcdd2; }

        /* Flash */
        .flash {
            padding: 12px 18px; border-radius: 8px; margin-bottom: 20px;
            background: #e8f5e9; color: #2e7d32; border-left: 4px solid #2e7d32;
            font-size: .9rem; display: flex; align-items: center; gap: 8px;
        }

        /* Empty state */
        .empty-state { text-align: center; padding: 60px 20px; color: #bbb; }
        .empty-state i { font-size: 3rem; display: block; margin-bottom: 12px; }
        .empty-state p { font-size: .95rem; }

        /* Select de cambio rápido de estado */
        .estado-select {
            padding: 4px 8px; border: 1px solid #ddd; border-radius: 6px;
            font-size: .8rem; cursor: pointer; background: white;
            max-width: 160px;
        }

        .cliente-info small { color: #aaa; font-size: .78rem; display: block; }
    </style>
</head>
<body>

<?= view('partials/navbar') ?>

<div class="container">

    <?php if (session()->getFlashdata('mensaje')): ?>
        <div class="flash">
            <i class="ri-checkbox-circle-line"></i>
            <?= session()->getFlashdata('mensaje') ?>
        </div>
    <?php endif; ?>

    <?php
    // Calcular totales por estado
    $porEstado = array_fill_keys(array_keys($estados), 0);
    foreach ($pedidos as $p) {
        $st = $p['status_actual'] ?? 'pedido';
        if (isset($porEstado[$st])) $porEstado[$st]++;
    }
    $pendientes  = ($porEstado['pedido'] + $porEstado['pedido_confirmado']);
    $enTransito  = $porEstado['en_transito'];
    $completados = ($porEstado['venta_confirmada'] + $porEstado['pagado']);
    $cancelados  = $porEstado['cancelado'];
    ?>

    <!-- KPIs clicables -->
    <div class="kpis">
        <div class="kpi" onclick="filtrar(this,'todos')" data-estado="todos">
            <div class="kpi-icon"><i class="ri-file-list-3-line" style="color:#2e7d32"></i></div>
            <div>
                <div class="kpi-valor"><?= count($pedidos) ?></div>
                <div class="kpi-label">Total pedidos</div>
            </div>
        </div>
        <div class="kpi" style="border-color:#f57c00" onclick="filtrar(this,'pedido')" data-estado="pedido">
            <div class="kpi-icon" style="background:#fff3e0"><i class="ri-time-line" style="color:#f57c00"></i></div>
            <div>
                <div class="kpi-valor" style="color:#f57c00"><?= $pendientes ?></div>
                <div class="kpi-label">Pendientes</div>
            </div>
        </div>
        <div class="kpi" style="border-color:#1565c0" onclick="filtrar(this,'en_transito')" data-estado="en_transito">
            <div class="kpi-icon" style="background:#e3f2fd"><i class="ri-truck-line" style="color:#1565c0"></i></div>
            <div>
                <div class="kpi-valor" style="color:#1565c0"><?= $enTransito ?></div>
                <div class="kpi-label">En tránsito</div>
            </div>
        </div>
        <div class="kpi" style="border-color:#1b5e20" onclick="filtrar(this,'venta_confirmada')" data-estado="venta_confirmada">
            <div class="kpi-icon" style="background:#e8f5e9"><i class="ri-checkbox-circle-line" style="color:#1b5e20"></i></div>
            <div>
                <div class="kpi-valor" style="color:#1b5e20"><?= $completados ?></div>
                <div class="kpi-label">Completados</div>
            </div>
        </div>
        <div class="kpi" style="border-color:#c62828" onclick="filtrar(this,'cancelado')" data-estado="cancelado">
            <div class="kpi-icon" style="background:#ffebee"><i class="ri-close-circle-line" style="color:#c62828"></i></div>
            <div>
                <div class="kpi-valor" style="color:#c62828"><?= $cancelados ?></div>
                <div class="kpi-label">Cancelados</div>
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="toolbar">
        <div class="toolbar-left">
            <div class="search-box">
                <i class="ri-search-line"></i>
                <input type="text" id="buscador" placeholder="Buscar pedido, cliente..." oninput="buscar()">
            </div>
            <div class="filtros" id="filtros-estado">
                <button class="filtro-chip activo" style="background:#2e7d32;border-color:#2e7d32"
                    onclick="filtrar(this,'todos')">Todos</button>
                <?php foreach ($estados as $key => $label): ?>
                <button class="filtro-chip"
                    style="--c:<?= $colores[$key] ?>"
                    onclick="filtrar(this,'<?= $key ?>')">
                    <?= $label ?>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
        <a href="<?= base_url('pedidos/crear') ?>" class="btn-primary">
            <i class="ri-add-line"></i> Nuevo Pedido
        </a>
    </div>

    <!-- Tabla de pedidos -->
    <div class="card">
        <div class="card-header">
            <h2><i class="ri-file-list-3-line"></i> Control de Pedidos</h2>
            <span style="font-size:.82rem;color:#aaa" id="contador-registros">
                <?= count($pedidos) ?> registros
            </span>
        </div>

        <table id="tabla-pedidos">
            <thead>
                <tr>
                    <th>Pedido</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Repartidor</th>
                    <th>Estado</th>
                    <th>Cambiar estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($pedidos)): ?>
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="ri-file-list-3-line"></i>
                            <p>No hay pedidos registrados aún.</p>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($pedidos as $p):
                    $st    = $p['status_actual'] ?? 'pedido';
                    $color = $colores[$st] ?? '#999';
                ?>
                <tr data-status="<?= $st ?>" data-buscar="<?= strtolower(esc($p['nombre'] . ' ' . $p['ap_paterno'] . ' ' . $p['id_pedido'])) ?>">
                    <td>
                        <strong style="color:#2e7d32">#<?= esc($p['id_pedido']) ?></strong>
                    </td>
                    <td><?= date('d/m/Y', strtotime($p['fecha'])) ?></td>
                    <td class="cliente-info">
                        <strong><?= esc($p['nombre']) ?> <?= esc($p['ap_paterno']) ?></strong>
                        <small><i class="ri-phone-line"></i> <?= esc($p['telefono']) ?></small>
                    </td>
                    <td>
                        <small><?= esc($p['rep_nombre']) ?> <?= esc($p['rep_ap']) ?></small>
                    </td>
                    <td>
                        <span class="badge" style="background:<?= $color ?>">
                            <?= esc($estados[$st] ?? $st) ?>
                        </span>
                    </td>
                    <td>
                        <!-- Cambio rápido de estado directo desde la tabla -->
                        <form method="POST"
                            action="<?= base_url('pedidos/cambiar-status/' . $p['id_pedido']) ?>">
                            <?= csrf_field() ?>
                            <select name="estado" class="estado-select"
                                onchange="this.form.submit()"
                                style="border-color:<?= $color ?>">
                                <?php foreach ($estados as $key => $label): ?>
                                <option value="<?= $key ?>"
                                    <?= ($st === $key) ? 'selected' : '' ?>>
                                    <?= $label ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </td>
                    <td>
                        <a href="<?= base_url('pedidos/ver/' . $p['id_pedido']) ?>"
                            class="btn-icon btn-ver" title="Ver detalle">
                            <i class="ri-eye-line"></i>
                        </a>
                        <a href="<?= base_url('pedidos/eliminar/' . $p['id_pedido']) ?>"
                            class="btn-icon btn-del" title="Eliminar"
                            onclick="return confirm('¿Eliminar pedido #<?= $p['id_pedido'] ?>?')">
                            <i class="ri-delete-bin-line"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</div><!-- /container -->

<script>
const coloresEstado = <?= json_encode($colores) ?>;

// Filtrar por estado (chips y KPIs)
function filtrar(el, estado) {
    // Actualizar chips activos
    document.querySelectorAll('.filtro-chip').forEach(c => {
        c.classList.remove('activo');
        c.style.background = '';
        c.style.borderColor = '';
        c.style.color = '';
    });

    if (el.classList.contains('filtro-chip')) {
        el.classList.add('activo');
        const c = coloresEstado[estado] || '#2e7d32';
        el.style.background = estado === 'todos' ? '#2e7d32' : c;
        el.style.borderColor = estado === 'todos' ? '#2e7d32' : c;
        el.style.color = 'white';
    }

    let visible = 0;
    document.querySelectorAll('#tabla-pedidos tbody tr').forEach(fila => {
        const mostrar = estado === 'todos' || fila.dataset.status === estado;
        fila.style.display = mostrar ? '' : 'none';
        if (mostrar) visible++;
    });

    document.getElementById('contador-registros').textContent = visible + ' registros';
}

// Búsqueda en tiempo real
function buscar() {
    const txt = document.getElementById('buscador').value.toLowerCase();
    let visible = 0;
    document.querySelectorAll('#tabla-pedidos tbody tr').forEach(fila => {
        const coincide = fila.dataset.buscar?.includes(txt);
        fila.style.display = coincide ? '' : 'none';
        if (coincide) visible++;
    });
    document.getElementById('contador-registros').textContent = visible + ' registros';
}
</script>

</body>
</html>