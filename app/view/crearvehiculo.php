
<?php 

include 'includes/header.php';

?>

    <main class="main">

        <h2>Nuevo vehículo</h2>


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
        <form method="POST" action="/flota/crear">
            <div class="info-usuario-container">

                
        
                <div>
                    <span>Categoría:</span>
                    <select name="idCategoria" id="categoria">
                        <?php foreach ($categorias as $categoria): ?>
                        <option value="<?php echo $categoria["id"];  ?>" <?php echo (isset($_SESSION['form_data']['grupo_vehiculo']) && $_SESSION['form_data']['grupo_vehiculo'] === $categoria["id_categoria"]) ? 'selected' : ''; ?>>
                            <?php echo $categoria["id_categoria"];  ?> - <?php echo $categoria["nombre_categoria"];  ?> 
                        </option>
                        <?php endforeach;?>   
                    </select>
                </div>
        
                <div>
                    <span for="matricula">Matrícula:</span>
                    <input type="text" name="matricula" id="matricula">
                </div>
               
        
                <div class="nested-grid">
                    <div>
                        <span for="marca">Marca:</span>
                        <input type="text" name="marca" id="marca">
                    </div>
                    <div>
                        <span for="modelo">Modelo:</span>
                        <input type="text" name="modelo" id="modelo">
                    </div>
                </div>
              
        
                <div>
                    <span for="kilometrosActuales">Kilómetros actuales:</span>
                    <input type="number" name="kilometrosActuales">
                </div>
        
        
            </div>
            <input type="submit" class="boton boton-main" value="Registrar">
        </form>
    </div>

    </main>

    <?php 

include 'includes/footer.php';

?>