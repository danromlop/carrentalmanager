
<?php 

include 'includes/header.php';

?>

    <main class="main">

        <h2>Editar vehículo</h2>


     <hr>
    <div class="w60">
        
        <?php
            if (isset($_SESSION['errores_crearV'])) {
    
                foreach ($_SESSION['errores_crearV'] as $error) {
                    echo '<p class="error">' . htmlspecialchars($error) . '</p>';
                }
    
                unset($_SESSION['errores_crearV']); 
                }
        ?>
    </div>

    <div class="w60">
        
        <form method="POST" action="/flota/editarVehiculo">
            <div class="info-usuario-container">
        
                <div>
                    <span>Categoría:</span>
                    <select name="id_categoria" id="categoria">
                        <?php foreach ($categorias as $categoria): ?>
                        <option value="<?php echo $categoria["id"];  ?>" <?php echo ($vehiculo["id_categoria"] == $categoria["id_categoria"]) ? 'selected' : ''; ?>>
                            <?php echo $categoria["id_categoria"];  ?> - <?php echo $categoria["nombre_categoria"];  ?> 
                        </option>
                        <?php endforeach;?>   
                    </select>
                </div>
        
                <div>
                    <span for="matricula">Matrícula:</span>
                    <input type="text" name="matricula" id="matricula" value="<?php echo $vehiculo["matricula"]; ?>">
                </div>
               
        
                <div class="nested-grid">
                    <div>
                        <span for="marca">Marca:</span>
                        <input type="text" name="marca" id="marca" value="<?php echo $vehiculo["marca"]; ?>">
                    </div>
                    <div>
                        <span for="modelo">Modelo:</span>
                        <input type="text" name="modelo" id="modelo" value="<?php echo $vehiculo["modelo"]; ?>">
                    </div>
                </div>
              
        
                <div>
                    <span for="kilometrosActuales">Kilómetros actuales:</span>
                    <input type="number" name="kilometrosActuales" value="<?php echo $vehiculo["kilometros_actuales"]; ?>">
                </div>
                <input type="hidden" name="matriculaEditar" id="matricula" value="<?php echo $vehiculo["matricula"]; ?>">
        
        
            </div>
            <input type="hidden" name="id" value="<?php echo $vehiculo["id"] ?>">
            <input type="submit" class="boton boton-main" value="Actualizar">
        </form>
    </div>

    </main>

    <?php 

include 'includes/footer.php';

?>