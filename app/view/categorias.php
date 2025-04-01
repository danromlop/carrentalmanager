
<?php 

include 'includes/header.php';

?>

    <main class="main">

        <h2>Lista de categorías</h2>

        

     <hr>
     <div class="w70">
            <?php
                if (isset($_SESSION['success_crearC'])) {
                    echo '<p class="success">' . htmlspecialchars($_SESSION['success_crearC']) . '</p>';
                    unset($_SESSION['success_crearC']);
                }
            ?>
        </div>



    <div class="w70 tabla-flota">
        <table id="tabla">
            <thead>
                <tr>
                    <th onclick="ordenarTabla(0)">ID </th>
                    <th onclick="ordenarTabla(1)">Nombre</th>
                    <?php if ($_SESSION['tipo_usuario'] == 'Responsable'): ?>
                    <th>Acción</th>
                    <?php endif; ?>
                  
                </tr>
            </thead>
            <tbody>

                <?php foreach($categorias as $index => $categoria):?>
                <tr>
                    <td><?php echo $categoria['id_categoria'];?></td>
                    <td><?php echo $categoria['nombre_categoria'];?></td>
                    <?php if ($_SESSION['tipo_usuario'] == 'Responsable'): ?>
                    <td class="accion-tabla">
                        <form method="POST" action="/categorias/editar">
                            <input type="hidden" name="id_categoria" value="<?php echo $categoria["id"] ?>">
                            <button class="no-boton" type="submit"><img class=accion-tabla-img src="/public/img/edit.svg"></button>
                        </form>
                        

                        <a onclick="confirmarEliminar(<?php echo $index; ?>)" title="Eliminar"><img class=accion-tabla-img src="/public/img/remove.svg"></a>
                        <form id="formEliminar<?php echo $index; ?>" method="POST" action="/categorias/eliminar">
                            <input type="hidden" name="id_categoria" value="<?php echo $categoria["id_categoria"] ?>">
                        </form>
                        


                    </td>
                    <?php endif; ?>
                </tr>
                    

                <?php endforeach;?>


                
                
            </tbody>
        </table>
      
    </div>

    <div id="confirmEliminar" class="aviso-eliminar">
        <div class="aviso-eliminar-content">
            <p>¿Estás seguro de que deseas eliminar esta categoría?</p>
            <button class="boton boton-main" id="confirmarBoton">Sí, eliminar</button>
            <button class="boton boton-main" id="cancelarBoton">Cancelar</button>
        </div>
    </div>

    </main>
    <script src="/public/js/script.js"></script>
    <?php 

include 'includes/footer.php';

?>