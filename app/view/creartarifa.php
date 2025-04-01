
<?php 

include 'includes/header.php';

?>

    <main class="main">

        <h2>Nueva tarifa</h2>


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
        
        <form method="POST" action="/tarifas/crearTarifa">
            <div class="info-usuario-container">
        
                <div>
                    <span>Código tarifa:</span>
                    <input type="text" name="codigo_tarifa">
                </div>

                <div>
                    <span>Nombre tarifa:</span>
                    <input type="text" name="nombre_tarifa">
                </div>

                <div>
                    <span>Precio diario:</span>
                    <input type="number" name="precio_diario">
                </div>

        
            </div>
            <input type="submit" class="boton boton-main" value="Crear">
        </form>
    </div>

    </main>

    <?php 

include 'includes/footer.php';

?>