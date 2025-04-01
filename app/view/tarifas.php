
<?php 

include 'includes/header.php';

?>

    <main class="main">

        <h2>Lista de tarifas</h2>

        

     <hr>
     <div class="w70">
            <?php
                if (isset($_SESSION['success_crearT'])) {
                    echo '<p class="success">' . htmlspecialchars($_SESSION['success_crearT']) . '</p>';
                    unset($_SESSION['success_crearT']);
                }
            ?>
    </div>
    <div class="w70 tabla-flota">
        <table id="tabla">
            <thead>
                <tr>
                    <th onclick="ordenarTabla(0)">Código </th>
                    <th onclick="ordenarTabla(1)">Tarifa</th>
                    <th onclick="ordenarTabla(2)">Precio diario</th>
                    <?php if ($_SESSION['tipo_usuario'] == 'Responsable'): ?>
                    <th>Acción</th>
                    <?php endif; ?>
                  
                    
                </tr>
            </thead>
            <tbody>

                <?php foreach($tarifas as $index => $tarifa):?>
                <tr>
                    <td><?php echo $tarifa['codigo_tarifa'];?></td>
                    <td><?php echo $tarifa['nombre_tarifa'];?></td>
                    <td><?php echo $tarifa['precio_diario'];?>€</td>
                    <?php if ($_SESSION['tipo_usuario'] == 'Responsable'): ?>
                    <td class="accion-tabla">
                        <form method="POST" action="/tarifas/editar">
                            <input type="hidden" name="id_tarifa" value="<?php echo $tarifa["id"] ?>">
                            <button class="no-boton" type="submit"><img class=accion-tabla-img src="/public/img/edit.svg"></button>
                        </form>
                        

                        <a onclick="confirmarEliminar(<?php echo $index; ?>)" title="Eliminar"><img class=accion-tabla-img src="/public/img/remove.svg"></a>
                        <form id="formEliminar<?php echo $index; ?>" method="POST" action="/tarifas/eliminar">
                            <input type="hidden" name="id_tarifa" value="<?php echo $tarifa["id"] ?>">
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
            <p>¿Estás seguro de que deseas eliminar esta tarifa?</p>
            <button class="boton boton-main" id="confirmarBoton">Sí, eliminar</button>
            <button class="boton boton-main" id="cancelarBoton">Cancelar</button>
        </div>
    </div>

    </main>
    <script src="/public/js/script.js"></script>
    <?php 

include 'includes/footer.php';

?>