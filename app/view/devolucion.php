
<?php 

include 'includes/header.php';

?>

    <main class="main">

        <h2>Devolución</h2>

        <div class="buscar-form-container">
    
            <form method="POST" action="/reserva/buscarDevolucion">
                    
                    
                    <div class="buscar-input">
                        <label for="matricula">Matrícula:</label> 
                        <input type="text" name="matricula" id="matricula">
                        
                    </div>
                    <input class="boton boton-main" type="submit" value="Buscar">
    
            </form>

          
        </div>

     <hr>

        <div class="w60">
            <?php if (isset($error)): ?>
                    <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
        </div>

    </main>

    <?php 

include 'includes/footer.php';

?>