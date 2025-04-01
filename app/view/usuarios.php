
<?php 

include 'includes/header.php';

?>

    <main class="main">

        <h2>Lista de usuarios</h2>

        

    <hr>
    <div class="w70">
            <?php
                if (isset($_SESSION['success_crearU'])) {
                    echo '<p class="success">' . htmlspecialchars($_SESSION['success_crearU']) . '</p>';
                    unset($_SESSION['success_crearU']);
                }
            ?>
    </div>
    <div class="w70 tabla-flota">
        <table id="tabla">
            <thead>
                <tr>
                    <th onclick="ordenarTabla(0)">Id </th>
                    <th onclick="ordenarTabla(1)">Usuario</th>
                    <th onclick="ordenarTabla(2)">Nombre empleado</th>
                    <th onclick="ordenarTabla(3)">Nivel</th>
                    <th>Acción</th>
                  
                    
                </tr>
            </thead>
            <tbody>

                <?php foreach($usuarios as $index => $usuario):?>
                <tr>
                    <td><?php echo $usuario['id_usuario'];?></td>
                    <td><?php echo $usuario['nombre_usuario'];?></td>
                    <td><?php echo $usuario['nombre_empleado'];?></td>
                    <td><?php echo $usuario['tipo_usuario'];?></td>
                    <td class="accion-tabla">
                        <form method="POST" action="/usuario/editar">
                            <input type="hidden" name="nombre_usuario" value="<?php echo $usuario["nombre_usuario"] ?>">
                            <button class="no-boton" type="submit"><img class=accion-tabla-img src="/public/img/edit.svg"></button>
                        </form>
                        

                        <a onclick="confirmarEliminar(<?php echo $index; ?>)" title="Eliminar"><img class=accion-tabla-img src="/public/img/remove.svg"></a>
                        <form id="formEliminar<?php echo $index; ?>" method="POST" action="/usuario/eliminar">
                            <input type="hidden" name="id_usuario" value="<?php echo $usuario["id_usuario"] ?>">
                        </form>
                        


                    </td>
                </tr>
                    

                <?php endforeach;?>


                
                
            </tbody>
        </table>
      
    </div>

    <div id="confirmEliminar" class="aviso-eliminar">
        <div class="aviso-eliminar-content">
            <p>¿Estás seguro de que deseas eliminar este usuario?</p>
            <button class="boton boton-main" id="confirmarBoton">Sí, eliminar</button>
            <button class="boton boton-main" id="cancelarBoton">Cancelar</button>
        </div>
    </div>

    </main>
    <script src="/public/js/script.js"></script>
    <?php 

include 'includes/footer.php';

?>