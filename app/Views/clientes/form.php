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
            padding-bottom: 6px; border-bottom: 2px solid #e8f5e9; font-size: .95rem; }
        .fila { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-size: .85rem; color: #555;
            margin-bottom: 5px; font-weight: bold; }
        input, select {
            width: 100%; padding: 10px 12px; border: 1px solid #ddd;
            border-radius: 5px; font-size: .95rem;
        }
        input:focus, select:focus { outline: none; border-color: #2e7d32; }
        .form-footer { display: flex; gap: 12px; margin-top: 24px; }
        .btn { padding: 10px 22px; border: none; border-radius: 5px;
            cursor: pointer; font-size: .9rem; text-decoration: none; display: inline-block; }
        .btn-verde { background: #2e7d32; color: white; }
        .btn-gris  { background: #757575; color: white; }
        .btn:hover { opacity: .85; }
        .errores { background: #ffebee; border-left: 4px solid #c62828;
            padding: 12px 16px; border-radius: 5px; margin-bottom: 20px;
            color: #c62828; font-size: .88rem; }
        .errores ul { margin-left: 16px; }
        .ruta { color: #999; font-size: .83rem; margin-bottom: 16px; }
        .ruta a { color: #2e7d32; text-decoration: none; }
    </style>
</head>
<body>

<?= view('partials/navbar') ?>

<div class="container">
    <p class="ruta"><a href="<?= base_url('clientes') ?>">← Volver a Clientes</a></p>

    <div class="card">
        <h2><?= esc($titulo) ?></h2>
        <p class="subtitulo">Los campos marcados con * son obligatorios</p>

        <?php $errores = session()->getFlashdata('errores'); ?>
        <?php if (!empty($errores)): ?>
            <div class="errores">
                <strong>Corrige los errores:</strong>
                <ul><?php foreach ($errores as $e): ?>
                    <li><?= esc($e) ?></li>
                <?php endforeach; ?></ul>
            </div>
        <?php endif; ?>

        <form action="<?= $accion ?>" method="POST">
            <?= csrf_field() ?>
            <!-- ID de dirección para saber si actualizar o insertar -->
            <input type="hidden" name="id_direccion"
                    value="<?= esc($cliente['id_direccion'] ?? '') ?>">

            <!-- DATOS PERSONALES -->
            <div class="seccion">👤 Datos personales</div>

            <div class="fila">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="nombre" required maxlength="100"
                        value="<?= esc(old('nombre', $cliente['nombre'] ?? '')) ?>">
                </div>
                <div class="form-group">
                    <label>Apellido Paterno *</label>
                    <input type="text" name="ap_paterno" required maxlength="100"
                        value="<?= esc(old('ap_paterno', $cliente['ap_paterno'] ?? '')) ?>">
                </div>
            </div>

            <div class="fila">
                <div class="form-group">
                    <label>Apellido Materno</label>
                    <input type="text" name="ap_materno" maxlength="100"
                        value="<?= esc(old('ap_materno', $cliente['ap_materno'] ?? '')) ?>">
                </div>
                <div class="form-group">
                    <label>Teléfono *</label>
                    <input type="text" name="telefono" required maxlength="12"
                        placeholder="10 dígitos"
                        value="<?= esc(old('telefono', $cliente['telefono'] ?? '')) ?>">
                </div>
            </div>

            <!-- DIRECCIÓN -->
            <div class="seccion">📍 Dirección de entrega</div>

            <div class="fila">
                <div class="form-group">
                    <label>Calle *</label>
                    <input type="text" name="calle" required maxlength="100"
                        value="<?= esc(old('calle', $cliente['calle'] ?? '')) ?>">
                </div>
                <div class="form-group">
                    <label>Número *</label>
                    <input type="number" name="numero" required min="1"
                        value="<?= esc(old('numero', $cliente['numero'] ?? '')) ?>">
                </div>
            </div>

            <div class="fila">
                <div class="form-group">
                    <label>Colonia *</label>
                    <input type="text" name="colonia" required maxlength="100"
                        value="<?= esc(old('colonia', $cliente['colonia'] ?? '')) ?>">
                </div>
                <div class="form-group">
                    <label>Municipio *</label>
                    <input type="text" name="municipio" required maxlength="200"
                        value="<?= esc(old('municipio', $cliente['municipio'] ?? '')) ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Estado *</label>
                <input type="text" name="estado" required maxlength="150"
                    value="<?= esc(old('estado', $cliente['estado'] ?? '')) ?>">
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-verde">💾 Guardar Cliente</button>
                <a href="<?= base_url('clientes') ?>" class="btn btn-gris">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>

