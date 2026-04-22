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
        .navbar a { color: white; text-decoration: none; margin-left: 16px; }

        .container { max-width: 600px; margin: 40px auto; padding: 0 16px; }

        .card {
            background: white; border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,.1); padding: 30px;
        }
        .card h2 { color: #333; margin-bottom: 24px; font-size: 1.2rem; }

        .form-group { margin-bottom: 18px; }
        label { display: block; font-size: 0.85rem; color: #555; margin-bottom: 6px; font-weight: bold; }

        input[type="text"],
        textarea,
        select {
            width: 100%; padding: 10px 12px; border: 1px solid #ddd;
            border-radius: 5px; font-size: 0.95rem; color: #333;
            transition: border-color .2s;
        }
        input[type="text"]:focus,
        textarea:focus,
        select:focus {
            outline: none; border-color: #2e7d32;
        }
        textarea { resize: vertical; min-height: 80px; }

        .form-footer {
            display: flex; gap: 12px; margin-top: 24px;
        }
        .btn {
            padding: 10px 20px; border: none; border-radius: 5px;
            cursor: pointer; font-size: 0.9rem; text-decoration: none;
            display: inline-block;
        }
        .btn-verde  { background: #2e7d32; color: white; }
        .btn-gris   { background: #757575; color: white; }
        .btn:hover  { opacity: 0.85; }
        //esto es una prueba a ver si se ve 

        /* Errores de validación */
        .errores {
            background: #ffebee; border-left: 4px solid #c62828;
            padding: 12px 16px; border-radius: 5px; margin-bottom: 20px;
            color: #c62828; font-size: 0.88rem;
        }
        .errores ul { margin-left: 16px; }
        .errores li { margin-top: 4px; }

        .ruta { color: #999; font-size: 0.83rem; margin-bottom: 20px; }
        .ruta a { color: #2e7d32; text-decoration: none; }
    </style>
</head>
<body>

<nav class="navbar">
    <h1> FRUVER</h1>
    <div>
        <a href="<?= base_url('productos') ?>">Productos</a>
        <a href="<?= base_url('clientes') ?>">Clientes</a>
    </div>
</nav>

<div class="container">

    <p class="ruta">
        <a href="<?= base_url('productos') ?>">← Volver al catálogo</a>
    </p>

    <div class="card">
        <h2><?= esc($titulo) ?></h2>

        <!-- Mostrar errores de validación si existen -->
        <?php $errores = session()->getFlashdata('errores'); ?>
        <?php if (!empty($errores)): ?>
            <div class="errores">
                <strong>Corrige los siguientes errores:</strong>
                <ul>
                    <?php foreach ($errores as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!--
            $accion viene del controller:
            - Crear: base_url('productos/guardar')
            - Editar: base_url('productos/actualizar/{id}')
        -->
        <form action="<?= $accion ?>" method="POST">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="nombre">Nombre del producto *</label>
                <input
                    type="text"
                    name="nombre"
                    id="nombre"
                    required
                    maxlength="200"
                    placeholder="Ej: Manzana Roja"
                    value="<?= esc(old('nombre', $producto['nombre'] ?? '')) ?>"
                >
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea
                    name="descripcion"
                    id="descripcion"
                    placeholder="Describe brevemente el producto..."
                ><?= esc(old('descripcion', $producto['descripcion'] ?? '')) ?></textarea>
            </div>

            <div class="form-group">
                <label for="imagen">Nombre del archivo de imagen *</label>
                <input
                    type="text"
                    name="imagen"
                    id="imagen"
                    required
                    maxlength="50"
                    placeholder="Ej: manzana.jpg"
                    value="<?= esc(old('imagen', $producto['imagen'] ?? '')) ?>"
                >
                <small style="color:#999; font-size:0.8rem;">
                    Solo el nombre del archivo (ej: sandia.jpg). Coloca la imagen en public/img/
                </small>
            </div>

            <div class="form-group">
                <label for="categoria">Categoría *</label>
                <select name="categoria" id="categoria" required>
                    <option value="">-- Selecciona --</option>
                    <?php
                    $categorias = ['frutas' => '🍎 Frutas', 'verduras' => '🥬 Verduras', 'hiervas' => '🌿 Hierbas'];
                    $seleccionada = old('categoria', $producto['categoria'] ?? '');
                    foreach ($categorias as $valor => $etiqueta):
                    ?>
                        <option value="<?= $valor ?>" <?= ($seleccionada === $valor) ? 'selected' : '' ?>>
                            <?= $etiqueta ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="unidad_venta">Unidad de venta *</label>
                <select name="unidad_venta" id="unidad_venta" required>
                    <option value="">-- Selecciona --</option>
                    <?php
                    $unidades = ['kilos' => 'Kilos', 'domos' => 'Domos', 'ramos' => 'Ramos'];
                    $seleccionada_u = old('unidad_venta', $producto['unidad_venta'] ?? '');
                    foreach ($unidades as $valor => $etiqueta):
                    ?>
                        <option value="<?= $valor ?>" <?= ($seleccionada_u === $valor) ? 'selected' : '' ?>>
                            <?= $etiqueta ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-verde">💾 Guardar</button>
                <a href="<?= base_url('productos') ?>" class="btn btn-gris">Cancelar</a>
            </div>

        </form>
    </div>
</div>

</body>
</html>