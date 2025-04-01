
<?php 

include 'includes/header.php';

?>

    <main class="main">

        <h2>Nueva categoria</h2>


     <hr>

    <div class="w60">
        <?php
            if (isset($_SESSION['errores_crearC'])) {
                foreach ($_SESSION['errores_crearC'] as $error) {
                    echo '<p class="error">' . htmlspecialchars($error) . '</p>';
                }
        
            unset($_SESSION['errores_crearC']); 
            }
        ?>
    </div>

    <div class="w60">
        
        <form method="POST" action="/categorias/crearCategoria">
            <div class="info-usuario-container">
        
                <div>
                    <span>ID categoría:</span>
                    <input type="text" name="id_categoria">
                </div>

                <div>
                    <span>Nombre categoría:</span>
                    <input type="text" name="nombre_categoria">
                </div>

        
            </div>
            <input type="submit" class="boton boton-main" value="Crear">
        </form>
    </div>

    </main>

    <?php 

include 'includes/footer.php';

?>