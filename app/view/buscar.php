
<?php 

include 'includes/header.php';

?>

    <main class="main">

        <h2>Buscar reserva</h2>

        <div class="buscar-form-container">
    
            <form method="POST" action="/reserva/buscar">
                    
                    
                    <div class="buscar-input">
                        <label for="id_reserva">ID reserva:</label> 
                        <input type="text" name="id_reserva" id="id_reserva">
                        
                    </div>
                    <input class="boton boton-main" type="submit" value="Buscar">
    
            </form>

          
        </div>

     <hr>
    <div class="w60">
    
    <?php
        if (isset($_SESSION['success_eliminarR'])) {
            echo '<p class="success">' . htmlspecialchars($_SESSION['success_eliminarR']) . '</p>';
            unset($_SESSION['success_eliminarR']);
        }
    ?>

    <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>

    <?php endif; ?> 

        <?php if ($mostrarBuscar): ?>
        

            <div class="info-reserva-container">
        
                <div>
                    <span>Cliente:</span>
                    <p><?php echo $reserva['nombre_cliente']; ?></p>
                </div>
        
                <div>
                    <span>DNI:</span>
                    <p><?php echo $reserva['dni_cliente']; ?></p>
                </div>
                <div>
                    <span>Nº Carnet:</span>
                    <p><?php echo $reserva['numero_carnet_conducir']; ?></p>
                </div>
        
        
                 <div>
                    <span>Fecha nacimiento:</span>
                    <p><?php echo $reserva['fecha_nacimiento']; ?></p>
                </div>
        
                <div class="nested-grid">
                    <div>
                        <span>Fecha exp:</span>
                        <p><?php echo $reserva['fecha_expedicion']; ?></p>
                    </div>
        
                    <div>
                        <span>Fecha cad:</span>
                        <p><?php echo $reserva['fecha_caducidad']; ?></p>
                    </div>
                </div>
                <div>
                    <span>Dirección:</span>
                    <p><?php echo $reserva['direccion']; ?></p>
                </div>
        
        
        
                <div class="nested-grid">
                    <div>
                        <span>Alquiler:</span>
                        <p><?php echo $reserva['fecha_inicio']; ?></p> <!-- fecha hoy-->
                    </div>
        
                    <div>
                        <span>Devolución:</span>
                        <p><?php echo $reserva['fecha_fin']; ?></p>
                    </div>
                </div>
                <div>
                    <span>Tarifa:</span>
                    <p><?php echo $reserva['codigo_tarifa']; ?></p>
                </div>
        
                <div>
                    <span>Grupo Reservado:</span>
                    <p><?php echo $reserva['id_categoria_coche'] . " - " . $reserva['nombre_categoria'] ?> </p>
                </div>
        
                <div>
                    <span>Estado:</span>
                    <p><?php echo $reserva['estado']; ?></p>
                </div>
        
        
        
        
            </div>
            <?php if ($reserva['estado'] == 'reservada'): ?>
            <a href="/reserva/editar/<?php echo $reserva['id_reserva']; ?>" class="boton boton-main">Editar</a>
            <?php if ($reserva["fecha_inicio"] == $hoy):?>
            <a href="/reserva/alquilar/<?php echo $reserva['id_reserva']; ?>" class="boton boton-main">Alquilar</a>
            <?php endif; ?>
            <?php if ($_SESSION['tipo_usuario'] == 'Responsable'): ?>
            <a class="boton boton-eliminar" onclick="confirmarEliminar(<?php echo $reserva['id_reserva']; ?>)" title="Eliminar">Cancelar</a>
            <form id="formEliminar<?php echo $reserva['id_reserva'];?>" method="POST" action="/reserva/eliminar">
                <input type="hidden" name="id_reserva" value="<?php echo $reserva["id_reserva"] ?>">
            </form>
            <?php endif; ?>
            <?php endif; ?>
            
        <?php endif; ?> 
    </div>

     <!--modal aviso eliminar -->
     <div id="confirmEliminar" class="aviso-eliminar">
        <div class="aviso-eliminar-content">
            <p>¿Estás seguro de que deseas cancelar esta reserva?</p>
            <button class="boton boton-main" id="confirmarBoton">Sí, cancelar</button>
            <button class="boton boton-main" id="cancelarBoton">No, salir</button>
        </div>
    </div>

    </main>

    <?php 

include 'includes/footer.php';

?>