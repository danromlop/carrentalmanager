
<?php 

include 'includes/header.php';

?>

    <main class="main">

        <h2>Editar reserva</h2>

        

        <form method="POST" action="/reserva/nueva/crear">
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
            
            unset($_SESSION['errores_crear']); // Limpiar el array después de mostrar los errores
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

                    <div class="nueva-fecha">
                        <div>
                            <label for="fecha_inicio">Fecha inicio:</label>
                            <input type="date" name="fecha_inicio" value="<?php echo $reserva["fecha_inicio"]?>">
                        </div>
                        
                        <div>
                            <label for="fecha_fin">Fecha fin:</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" value="<?php echo $reserva["fecha_fin"]?>">
                        </div>
                    </div>

                   
                    
                    <div class="nueva-form-grid">
                        <div>
                            <!--columna izq-->

                            <div>
                                <label for="nombre">Nombre:</label>
                                <input type="text" name="nombre" id="nombre" value="<?php echo $reserva["nombre_cliente"]?>">
                            </div>
                            <div>
                                <label for="dni">DNI:</label> 
                                <input type="text" name="dni" id="dni" value="<?php echo $reserva["dni_cliente"] ?>">
                            </div>
                            <div>
                                <label for="carnet">Nº Carnet Conducir:</label>
                                <input type="text" name="carnet" id="carnet" value="<?php echo $reserva["numero_carnet_conducir"]?>">
                            </div>
                        </div>

                        <div>
                            <!--columna drcha-->

                            <div>
                                <label for="apellidos">Apellidos:</label>
                                <input type="text" name="apellidos" id="apellidos" value="<?php echo $reserva["apellidos"] ?>">
                            </div>
                            <div>
                                <label for="fecha_nacimiento">Fecha nacimiento:</label>
                                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="<?php echo $reserva["fecha_nacimiento"] ?>">
                            </div>
                            <div class="nueva-fecha-nac">
                                <div>
                                    <label for="fecha_expedicion">Fecha exp:</label>
                                    <input type="date" name="fecha_expedicion" id="fecha_expedicion" value="<?php echo $reserva["fecha_expedicion"]?>">
                                </div>
                                
                                <div>
                                    <label for="fecha_caducidad">Fecha caducidad:</label>
                                    <input type="date" name="fecha_caducidad" id="fecha_caducidad" value="<?php echo $reserva["fecha_caducidad"]?>">
                                </div>
                            </div>


                        </div>

                    </div>
                    
                    

                    <div>
                        <label for="direccion">Dirección:</label>
                        <input type="text" name="direccion" id="direccion" value="<?php echo $reserva["direccion"] ?>">
                    </div>
                   
                    <label for="grupo">Grupo reservado:</label>
                    <select name="grupo_vehiculo" id="grupo">
                        <?php foreach ($categorias as $categoria): ?>
                        <option value="<?php echo $categoria["id"];  ?>" <?php echo 
                        
                        (isset($_SESSION['form_data']['grupo_vehiculo']) && $_SESSION['form_data']['grupo_vehiculo'] === $categoria["id_categoria"]) ||
                        (!isset($_SESSION['form_data']['grupo_vehiculo']) && $categoria["id_categoria"] === $reserva["id_categoria"])
                        
                        ? 'selected' : ''; ?> >
                            <?php echo $categoria["id_categoria"];  ?> - <?php echo $categoria["nombre_categoria"];  ?> 
                        </option>
                        <?php endforeach;?>   
                    </select>
    
                    <span for="tarifa">Tarifa reservada:</span>
                    <select name="tarifa" id="tarifa">
                        <?php foreach ($tarifas as $tarifa): ?>
                            <option value="<?php echo $tarifa["id"];  ?>" <?php echo 
                            (isset($_SESSION['form_data']['tarifa']) && $_SESSION['form_data']['tarifa'] === $tarifa["codigo_tarifa"]) ||
                            (!isset($_SESSION['form_data']['tarifa']) && $tarifa["codigo_tarifa"] === $reserva["codigo_tarifa"]) 
                            ? 'selected' : ''; ?>>

                                <?php echo $tarifa["codigo_tarifa"];  ?> - <?php echo $tarifa["nombre_tarifa"];  ?> - <?php echo $tarifa["precio_diario"];  ?>€/Día
                            </option>
                        <?php endforeach;?> 
                    </select>
                    <input type="hidden" name="reserva_id" value="<?php echo $reserva['id_reserva']; ?>">

    
           <input class="boton boton-main " type="submit" value="Editar">
        </div>
        
        </form>
    </main>

    <?php 

include 'includes/footer.php';

?>