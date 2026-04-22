<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="<?= base_url('actualiza') ?>" method="post">
        <input type='hidden' name='id_cliente' value='<?=$cliente['id_cliente']?>' required/> <br>
        <label>Nombre:</label>
        <input type='text' name='nombre' value='<?=$cliente['nombre']?>' required/> <br>
        <label>Dirección:</label>
        <input type='text' name='direccion' value='<?=$cliente['direccion']?>' required/> <br>
        <label>RFC:</label>
        <input type='text' name='rfc' value='<?=$cliente['rfc']?>' required/> <br>
        <label>Teléfono:</label>
        <input type='text' name='telefono' value='<?=$cliente['telefono']?>' required/> <br>
        
        <input type='submit' value='Actualizar Cliente'/> <br>
    </form>
</body>
</html>