<!DOCTYPE html>
<html lang="en">
<head>
    
</head>
<body>
    <form action="<?= base_url('guarda_cliente') ?>" method="post">
        <label>Nombre:</label>
        <input type="text" name="nombre" required/> <br>
        <label>Dirección:</label>
        <input type="text" name="direccion" required/> <br>
        <label>Teléfono:</label>
        <input type="text" name="telefono" required/> <br>
        <label>RFC:</label>
        <input type="text" name="rfc" required/> <br>
        <input type="submit" value="Registrar Cliente" required/> <br>
    </form>
</body>
</html>