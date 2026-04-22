<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clientes | FRUVER</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .navbar { background: #2e7d32; color: white; padding: 12px 24px;
            display: flex; align-items: center; justify-content: space-between; }
        .navbar h1 { font-size: 1.3rem; }
        .navbar a { color: white; text-decoration: none; margin-left: 16px; font-size: .9rem; }
        .container { max-width: 1100px; margin: 30px auto; padding: 0 16px; }
        .card { background: white; border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,.1); padding: 24px; }
        .card-header { display: flex; justify-content: space-between;
            align-items: center; margin-bottom: 20px; }
        .card-header h2 { color: #333; font-size: 1.2rem; }
        .btn { padding: 8px 16px; border: none; border-radius: 5px;
            cursor: pointer; font-size: .85rem; text-decoration: none; display: inline-block; }
        .btn-verde { background: #2e7d32; color: white; }
        .btn-azul  { background: #1565c0; color: white; }
        .btn-rojo  { background: #c62828; color: white; }
        .btn:hover { opacity: .85; }
        .alerta { padding: 12px 16px; border-radius: 5px; margin-bottom: 20px;
            background: #e8f5e9; color: #2e7d32; border-left: 4px solid #2e7d32; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f9f9f9; text-align: left; padding: 10px 12px;
            border-bottom: 2px solid #ddd; font-size: .85rem; color: #555; }
        td { padding: 10px 12px; border-bottom: 1px solid #eee;
            font-size: .9rem; vertical-align: middle; }
        tr:hover td { background: #fafafa; }
        .acciones { display: flex; gap: 8px; }
        .buscar { padding: 8px 12px; border: 1px solid #ddd; border-radius: 5px;
            font-size: .9rem; width: 280px; }
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
            <h2>👥 <?= esc($titulo) ?></h2>
            <div style="display:flex; gap:10px; align-items:center;">
                <input class="buscar" type="text" id="buscador"
                    placeholder="🔍 Buscar cliente..." onkeyup="buscar()">
                <a href="<?= base_url('clientes/crear') ?>" class="btn btn-verde">+ Nuevo Cliente</a>
            </div>
        </div>

        <table id="tabla">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre completo</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Municipio / Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($clientes)): ?>
                <tr>
                    <td colspan="6" style="text-align:center;color:#999;padding:30px;">
                        No hay clientes registrados.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($clientes as $c): ?>
                <tr>
                    <td><?= esc($c['id_cliente']) ?></td>
                    <td>
                        <strong>
                            <?= esc($c['nombre']) ?>
                            <?= esc($c['ap_paterno']) ?>
                            <?= esc($c['ap_materno'] ?? '') ?>
                        </strong>
                    </td>
                    <td><?= esc($c['telefono']) ?></td>
                    <td>
                        <?php if (!empty($c['calle'])): ?>
                            <?= esc($c['calle']) ?> #<?= esc($c['numero']) ?>,
                            Col. <?= esc($c['colonia']) ?>
                        <?php else: ?>
                            <span style="color:#bbb">Sin dirección</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($c['municipio'])): ?>
                            <?= esc($c['municipio']) ?>, <?= esc($c['estado']) ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="acciones">
                            <a href="<?= base_url('clientes/editar/' . $c['id_cliente']) ?>"
                                class="btn btn-azul">✏️ Editar</a>
                            <a href="<?= base_url('clientes/eliminar/' . $c['id_cliente']) ?>"
                                class="btn btn-rojo"
                                onclick="return confirm('¿Eliminar a <?= esc($c['nombre']) ?> <?= esc($c['ap_paterno']) ?>?')">
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
function buscar() {
    const texto = document.getElementById('buscador').value.toLowerCase();
    document.querySelectorAll('#tabla tbody tr').forEach(fila => {
        fila.style.display = fila.textContent.toLowerCase().includes(texto) ? '' : 'none';
    });
}
</script>
</body>
</html>