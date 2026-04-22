<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= esc($titulo) ?> | FRUVER</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }

        .navbar {
            background: #2e7d32; color: white; padding: 12px 24px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .navbar h1 { font-size: 1.3rem; }
        .navbar a { color: white; text-decoration: none; margin-left: 16px; font-size: 0.9rem; }

        .container { max-width: 1100px; margin: 30px auto; padding: 0 16px; }

        .card {
            background: white; border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,.1); padding: 24px;
        }

        .card-header {
            display: flex; justify-content: space-between;
            align-items: center; margin-bottom: 20px;
        }
        .card-header h2 { color: #333; font-size: 1.2rem; }

        .btn {
            padding: 8px 16px; border: none; border-radius: 5px;
            cursor: pointer; font-size: 0.85rem; text-decoration: none;
            display: inline-block;
        }
        .btn-verde  { background: #2e7d32; color: white; }
        .btn-azul   { background: #1565c0; color: white; }
        .btn-rojo   { background: #c62828; color: white; }
        .btn:hover  { opacity: 0.85; }

        /* Alerta de mensajes flash */
        .alerta {
            padding: 12px 16px; border-radius: 5px; margin-bottom: 20px;
            background: #e8f5e9; color: #2e7d32; border-left: 4px solid #2e7d32;
        }

        /* Badges de categoría */
        .badge {
            padding: 3px 10px; border-radius: 20px; font-size: 0.78rem; font-weight: bold;
        }
        .badge-frutas   { background: #fff3e0; color: #e65100; }
        .badge-verduras { background: #e8f5e9; color: #2e7d32; }
        .badge-hiervas  { background: #f3e5f5; color: #6a1b9a; }

        table { width: 100%; border-collapse: collapse; }
        th { background: #f9f9f9; text-align: left; padding: 10px 12px; border-bottom: 2px solid #ddd; font-size: 0.85rem; color: #555; }
        td { padding: 10px 12px; border-bottom: 1px solid #eee; font-size: 0.9rem; vertical-align: middle; }
        tr:hover td { background: #fafafa; }

        .acciones { display: flex; gap: 8px; }

        /* Filtros */
        .filtros { display: flex; gap: 8px; margin-bottom: 16px; flex-wrap: wrap; }
        .filtro-btn {
            padding: 5px 14px; border-radius: 20px; border: 1px solid #ccc;
            background: white; cursor: pointer; font-size: 0.82rem;
        }
        .filtro-btn.activo { background: #2e7d32; color: white; border-color: #2e7d32; }
    </style>
</head>
<body>

<?= view('partials/navbar') ?>

<div class="container">

    <?php if (session()->getFlashdata('mensaje')): ?>
        <div class="alerta"><?= session()->getFlashdata('mensaje') ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h2><?= esc($titulo) ?></h2>
            <a href="<?= base_url('productos/crear') ?>" class="btn btn-verde">+ Nuevo Producto</a>
        </div>

        <!-- Filtros rápidos por categoría -->
        <div class="filtros">
            <button class="filtro-btn activo" onclick="filtrar(this, 'todos')">Todos</button>
            <button class="filtro-btn" onclick="filtrar(this, 'frutas')">🍎 Frutas</button>
            <button class="filtro-btn" onclick="filtrar(this, 'verduras')">🥬 Verduras</button>
            <button class="filtro-btn" onclick="filtrar(this, 'hiervas')">🌿 Hierbas</button>
        </div>

        <table id="tabla-productos">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Categoría</th>
                    <th>Unidad Venta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($productos)): ?>
                    <tr>
                        <td colspan="6" style="text-align:center; color:#999; padding:30px;">
                            No hay productos registrados aún.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($productos as $p): ?>
                    <tr data-categoria="<?= esc($p['categoria']) ?>">
                        <td><?= esc($p['id_producto']) ?></td>
                        <td><strong><?= esc($p['nombre']) ?></strong></td>
                        <td><?= esc($p['descripcion'] ?? '—') ?></td>
                        <td>
                            <span class="badge badge-<?= esc($p['categoria']) ?>">
                                <?= ucfirst(esc($p['categoria'])) ?>
                            </span>
                        </td>
                        <td><?= esc($p['unidad_venta']) ?></td>
                        <td>
                            <div class="acciones">
                                <a href="<?= base_url('productos/editar/' . $p['id_producto']) ?>"
                                   class="btn btn-azul">✏️ Editar</a>

                                <a href="<?= base_url('productos/eliminar/' . $p['id_producto']) ?>"
                                   class="btn btn-rojo"
                                   onclick="return confirm('¿Seguro que deseas eliminar «<?= esc($p['nombre']) ?>»?')">
                                   🗑️ Eliminar
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Filtro por categoría sin recargar la página
function filtrar(boton, categoria) {
    document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('activo'));
    boton.classList.add('activo');

    document.querySelectorAll('#tabla-productos tbody tr').forEach(fila => {
        if (categoria === 'todos' || fila.dataset.categoria === categoria) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
}
</script>

</body>
</html>