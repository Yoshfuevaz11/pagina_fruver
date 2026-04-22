<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Merma | FRUVER</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .navbar { background: #2e7d32; color: white; padding: 12px 24px;
            display: flex; align-items: center; justify-content: space-between; }
        .navbar h1 { font-size: 1.3rem; }
        .navbar a { color: white; text-decoration: none; margin-left: 16px; }
        .container { max-width: 600px; margin: 30px auto; padding: 0 16px; }
        .card { background: white; border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,.1); padding: 30px; }
        .card h2 { color: #333; margin-bottom: 20px; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-size: .85rem; color: #555;
            margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 10px 12px;
            border: 1px solid #ddd; border-radius: 5px; font-size: .95rem; }
        textarea { resize: vertical; min-height: 90px; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #e65100; }
        .form-footer { display: flex; gap: 12px; margin-top: 24px; }
        .btn { padding: 10px 22px; border: none; border-radius: 5px;
            cursor: pointer; font-size: .9rem; text-decoration: none; display: inline-block; }
        .btn-naranja { background: #e65100; color: white; }
        .btn-gris    { background: #757575; color: white; }
        .btn:hover   { opacity: .85; }
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
    <p class="ruta"><a href="<?= base_url('inventario/mermas') ?>">← Volver a Mermas</a></p>

    <div class="card">
        <h2> Registrar Merma</h2>

        <?php $errores = session()->getFlashdata('errores'); ?>
        <?php if (!empty($errores)): ?>
            <div class="errores">
                <ul><?php foreach ($errores as $e): ?>
                    <li><?= esc($e) ?></li>
                <?php endforeach; ?></ul>
            </div>
        <?php endif; ?>

        <form action="<?= $accion ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-group">
                <label>Entrada afectada *</label>
                <select name="id_entrada" required>
                    <option value="">-- Selecciona la entrada --</option>
                    <?php foreach ($entradas as $e): ?>
                    <option value="<?= $e['id_entrada'] ?>"
                        <?= (old('id_entrada') == $e['id_entrada']) ? 'selected' : '' ?>>
                        #<?= $e['id_entrada'] ?> —
                        <?= esc($e['producto_nombre']) ?>
                        (<?= esc($e['fecha']) ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Cantidad en merma *</label>
                <input type="number" name="cantidad" required
                    min="0.01" step="0.01"
                    placeholder="Ej: 2.5"
                    value="<?= esc(old('cantidad')) ?>">
            </div>

            <div class="form-group">
                <label>Fecha de merma *</label>
                <input type="date" name="fecha" required
                    value="<?= esc(old('fecha', date('Y-m-d'))) ?>">
            </div>

            <div class="form-group">
                <label>Observaciones / Causa de la merma *</label>
                <textarea name="notas" required
                    placeholder="Ej: Producto golpeado durante acomodo..."
                ><?= esc(old('notas')) ?></textarea>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-naranja"> Guardar Merma</button>
                <a href="<?= base_url('inventario/mermas') ?>" class="btn btn-gris">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>