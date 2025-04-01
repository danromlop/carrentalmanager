
<?php 

include 'includes/header.php';

?>

    <main class="main">

        <h2>Flota</h2>

        

     <hr>
        <div class="w70">
            <?php
                if (isset($_SESSION['success_crearV'])) {
                    echo '<p class="success">' . htmlspecialchars($_SESSION['success_crearV']) . '</p>';
                    unset($_SESSION['success_crearV']);
                }
            ?>
        </div>



    <div class="w70 tabla-flota">
        


        <table id="tabla">
            <thead>
                <tr>
                    <th onclick="ordenarTabla(0)">Cat.</th>
                    <th onclick="ordenarTabla(1)">Matrícula</th>
                    <th onclick="ordenarTabla(2)">Marca</th>
                    <th onclick="ordenarTabla(3)">Modelo</th>
                    <th onclick="ordenarTabla(4)">Kilómetros</th>
                    <th onclick="ordenarTabla(5)">Estado</th>
                    <?php if ($_SESSION['tipo_usuario'] == 'Responsable'): ?>
                    <th>Acción</th>
                    <?php endif; ?>
                    
                </tr>
            </thead>
            <tbody>
                <?php foreach($flota as $index => $vehiculo):?>
                    <tr>
                    <td><?php echo $vehiculo["categoria_coche"] ?></td>
                    <td><?php echo $vehiculo["matricula"] ?></td>
                    <td><?php echo $vehiculo["marca"] ?></td>
                    <td><?php echo $vehiculo["modelo"] ?></td>
                    <td><?php echo $vehiculo["kilometros_actuales"] ?></td>
                    
                    <td><?php echo $vehiculo["alquilado"] ?  "En alquiler" : "Disponible" ?></td>
                    

                    <?php if ($_SESSION['tipo_usuario'] == 'Responsable'): ?>
                    <td class="accion-tabla">
                    
                    <?php if($vehiculo["alquilado"]) echo "En alquiler" ?>
                    <?php if(!$vehiculo["alquilado"]): ?>
                        <form method="POST" action="/flota/editar">
                            <input type="hidden" name="id" value="<?php echo $vehiculo["id"] ?>">
                            <button class="no-boton" type="submit"><img class=accion-tabla-img src="/public/img/edit.svg"></button>
                        </form>
                      

                        <a onclick="confirmarEliminar(<?php echo $index; ?>)" title="Eliminar"><img class=accion-tabla-img src="/public/img/remove.svg"></a>
                        <form id="formEliminar<?php echo $index; ?>" method="POST" action="flota/eliminar">
                            <input type="hidden" name="matricula" value="<?php echo $vehiculo["matricula"] ?>">
                        </form>
                        

                    <?php endif; ?>
                    </td>
                    
                    <?php endif; ?>
                   
                   
                </tr>

                <?php endforeach;?>
               
            </tbody>
        </table>
      
    </div>

    <!--modal aviso eliminar -->
    <div id="confirmEliminar" class="aviso-eliminar">
        <div class="aviso-eliminar-content">
            <p>¿Estás seguro de que deseas eliminar este vehículo?</p>
            <button class="boton boton-main" id="confirmarBoton">Sí, eliminar</button>
            <button class="boton boton-main" id="cancelarBoton">Cancelar</button>
        </div>
    </div>

    </main>



    
    
<?php 

include 'includes/footer.php';

?>