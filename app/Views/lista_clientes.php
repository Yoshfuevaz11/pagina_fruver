<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
     <meta name ="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
     <title>Lista de Clientes</title>
</head>
<body>
   <?php if(isset($mensaje)):?>
    <script>
        alert ("<?= $mensaje ?>");
    </script>

<?php endif; ?>
   <table>
    <thead>
        <tr>
            <th>No Cliente</th>
            <th>Cliente</th>
            <th>RFC</th>
            <th>Teléfono</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($datos_cliente as $cliente){
        echo "<tr>
            <td>".$cliente['id_cliente']."</td>
            <td>".$cliente['nombre']."</td>
            <td>".$cliente['rfc']."</td>
            <td>".$cliente['telefono']."</td>
             <td>
            <button class='btn' onclick='abrirIframe(".base_url('datosc/'.$cliente['id_cliente']).")><i class='fa fa-trash'></i></button>
             </td>
            <td><a href='".base_url('borrarc/'.$cliente['id_cliente'])."'><i class='fa-regular fa-trash-can'></i></a></td><td><a href='".base_url('borrarc/'.$cliente['id_cliente'])."'><i class='fa-regular fa-trash-can'></i></a></td>
        </tr>";}
        //<td><a href='".base_url('datosc/'.$cliente['id_cliente'])."'><i class='fa-regular fa-file-pen'></i></a></td>
        
        ?>
    </tbody>
   </table>
     <!--Ventana modal-->
     <div id="VModal" class="modal-prin">
        <div class="modal-contenido">
            <span class="cerrar" onclick="cierraIframe()">X</span>
            <iframe id="formcontenido" src="" width="300px" height="400px" frameborder="0">
            </iframe>

        </div>
     </div>
     <script>
    
            const modal = document.getElementById('VModal');
            const iframe = document.getElementById('formcontenido');
            
            function abrirIframe(url) {
                iframe.src = url;
                modal.style.display;
            }
            function cierraIframe() {
                modal.style.display = 'none';
                iframe.src = '';
            }
            window.onclick = function(event) {
                if (event.target == modal) {
                    cierraIframe();
                }
            }
            
    </script>

</body>
</html> 