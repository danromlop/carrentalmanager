
<?php 

include 'includes/header.php';

?>

    <main class="main">

        <h2>Editar tarifa</h2>


     <hr>

    <div class="w60">
        <?php
            if (isset($_SESSION['errores_crearT'])) {
                foreach ($_SESSION['errores_crearT'] as $error) {
                    echo '<p class="error">' . htmlspecialchars($error) . '</p>';
                }
        
            unset($_SESSION['errores_crearT']);
            }
        ?>
    </div>

    <div class="w60">
        
        <form method="POST" action="/tarifas/editarTarifa">
            <div class="info-usuario-container">
        
                <div>
                    <span>Código tarifa:</span>
                    <input type="text" name="codigo_tarifa" value="<?php echo $tarifa["codigo_tarifa"] ?>">
                </div>

                <div>
                    <span>Nombre tarifa:</span>
                    <input type="text" name="nombre_tarifa" value="<?php echo $tarifa["nombre_tarifa"] ?>">
                </div>

                <div>
                    <span>Precio diario:</span>
                    <input type="number" name="precio_diario" value="<?php echo $tarifa["precio_diario"] ?>">
                </div>
                <input type="hidden" name="id_tarifa" value="<?php echo $tarifa["id"] ?>">

        
            </div>
            <input type="submit" class="boton boton-main" value="Actualizar">
        </form>
    </div>

    </main>

    <?php 

include 'includes/footer.php';

?>