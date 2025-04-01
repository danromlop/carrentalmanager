
<?php 

include 'includes/header.php';

?>
    <main class="main">

        <h2>Alquilar</h2>

        

     <hr>


     <div class="w60">

     <div class="nueva-form-container">
                <?php
                //mensaje de error 
                    if (isset($_SESSION['error_crear'])) {
                        echo '<p class="error">' . htmlspecialchars($_SESSION['error_crear']) . '</p>';
                        unset($_SESSION['error_crear']);
                }
                if (isset($_SESSION['errores_crear'])) {
                    
                    foreach ($_SESSION['errores_crear'] as $error) {
                        echo '<p class="error">' . htmlspecialchars($error) . '</p>';
                    }
                    
                    unset($_SESSION['errores_crear']); 
                    }

                    if (isset($_SESSION['success_crear'])) {
                        echo '<p class="success">' . htmlspecialchars($_SESSION['success_crear']) . '</p>';
                        unset($_SESSION['success_crear']);
                }
                    if (isset($_SESSION['success_crear_existente'])) {
                        echo '<p class="success">' . htmlspecialchars($_SESSION['success_crear_existente']) . '</p>';
                        unset($_SESSION['success_crear_existente']); 

                }
                ?>
            </div>
         
            <form method="POST" action="/reserva/alquilarVehiculo">
            
         
                <div class="info-reserva-container">
            
                    <div class="nested-grid">
                        <div>
                            <span>Nombre:</span>
                            <input type="text" name="nombre" id="nombre" value="<?php if (isset($reserva)) echo $reserva['nombre_cliente']; ?>"></input>
                        </div>
                        <div>
                            <span>Apellidos:</span>
                            <input type="text" name="apellidos" id="apellidos" value="<?php if (isset($reserva)) echo $reserva['apellidos']; ?>"></input>
                        </div>
                    </div>
            
                    <div>
                        <span>DNI:</span>
                        <input type="text"  name="dni" id="dni" value="<?php echo $reserva['dni_cliente']; ?>"></input>
                    </div>
                    <div>
                        <span>Nº Carnet:</span>
                        <input type="text" name="carnet" id="carnet" value="<?php echo $reserva['numero_carnet_conducir']; ?>"></input>
                    </div>
            
            
                    <div>
                        <span>Fecha nacimiento:</span>
                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="<?php echo $reserva['fecha_nacimiento']; ?>"></input>
                    </div>
            
                    <div class="nested-grid">
                        <div>
                            <span>Fecha exp:</span>
                            <input type="date" name="fecha_expedicion" id="fecha_expedicion" value="<?php echo $reserva['fecha_expedicion']; ?>"></input>
                        </div>
            
                        <div>
                            <span>Fecha cad:</span>
                            <input  type="date" name="fecha_caducidad" id="fecha_caducidad" value="<?php echo $reserva['fecha_caducidad']; ?>"></input>
                        </div>
                    </div>
                    <div>
                        <span>Dirección:</span>
                        <input name="direccion" id="direccion" value="<?php echo $reserva['direccion']; ?>"></input>
                    </div>
            
            
                    <div class="nested-grid">
                        <div>
                            <span>Alquiler:</span>
                            <p><?php echo $reserva['fecha_inicio']; ?></p> 
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
                        <span>Matrícula:</span>
                        <input name="matricula" id="matricula" type="text" value=""></input>
                    </div>
                        <input type="hidden" name="id_cliente" value="<?php echo $reserva['id_cliente'] ?>">
            
            
            
            
                </div>
            
               
                <input type="hidden" name="reserva_id" value="<?php echo $reserva['id_reserva']; ?>">
                <input type="submit" class="boton boton-main" value="Alquilar">
            </form>
     </div>

    </main>

    <?php 

include 'includes/footer.php';

?>