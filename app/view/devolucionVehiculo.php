
<?php 

include 'includes/header.php';

?>

    <main class="main">

        <h2>Devolución</h2>

     <hr>
    <div class="w60">
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php 
            if (isset($_SESSION['error_devolver'])) {
                echo '<p class="error">' . htmlspecialchars($_SESSION['error_devolver']) . '</p>'; 
                unset($_SESSION['error_devolver']); 
            }
        ?>
        
        
        <form method="POST" action="/reserva/realizarDevolucion/<?php echo $reserva['id_reserva']; ?>">
            <div class="info-reserva-container">
        
                <div>
                    <span>Cliente:</span>
                    <p><?php echo $reserva["nombre_cliente"] . " " .  $reserva["apellidos"] ?></p>
                </div>
        
                <div>
                    <span>Vehículo:</span>
                    <p><?php echo $reserva["matricula"]?></p>
                    <input type="hidden" name="matricula" id="matricula" value="<?php echo $reserva["matricula"]?>">
                </div>
               
        
        
                <div class="nested-grid">
                    <div>
                        <span>Alquiler:</span>
                        <p><?php echo $reserva["fecha_inicio"]?></p> <!-- fecha hoy-->
                    </div>
        
                    <div>
                        <span>Devolución:</span>
                        <p><?php echo $reserva["fecha_fin"]?></p>
                    </div>
                </div>
              
        
                <div>
                    <span>Grupo Reservado:</span>
                    <p>Grupo <?php echo $reserva["id_categoria_coche"] . " - " . $reserva["nombre_categoria"]?></p>
                </div>
        
                <div class="nested-grid">
                    <div>
                        <span>Km salida:</span>
                        <p><?php echo $reserva["kilometros_salida"]?></p>
                        <input type="hidden" name="kilometrosSalida" id="kilometrosSalida" value="<?php echo $reserva["kilometros_salida"]?>">
                    </div>
                    <div>
                        <span for="kilometrosEntrada">Km entrada:</span>
                        <input type="number" name="kilometrosEntrada" id="kilometrosEntrada">
                    </div>
                </div>

                <div>
                    <span>Precio total:</span>
                    <p><?php echo $precioFinal . "€"?></p>
                </div>
        
        
        
        
            </div>
            <input type="submit" class="boton boton-main" value="Devolución">
        </form>
    </div>

    </main>

    <?php 

include 'includes/footer.php';

?>