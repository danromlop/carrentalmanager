
<?php 

include 'includes/header.php';

?>

    <main class="main">

        <h2>Devolución</h2>

     <hr>
    <div class="w60">
        
        <?php 
            if (isset($_SESSION['success_devolver'])) {
                echo '<p class="success">' . $_SESSION['success_devolver'] . '</p>'; 
                unset($_SESSION['success_devolver']); 
            }
        
         ?>
        <form >
            <div class="info-reserva-container">
        
                <div>
                    <span>Cliente:</span>
                    <p><?php echo $reserva["nombre_cliente"] . " " .  $reserva["apellidos"] ?></p>
                </div>
        
                <div>
                    <span>Vehículo:</span>
                    <p><?php echo $reserva["matricula"]?></p>
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
                    </div>
                    <div>
                        <span for="kilometrosSalida">Km entrada:</span>
                        <p><?php echo $kilometrosEntrada?></p>
                    </div>
                </div>

                <div>
                    <span>Estado:</span>
                    <p><?php echo $reserva["estado"]?></p>
                </div>
        
        
        
        
            </div>
            <a class="boton boton-main" href="/index">Volver</a>
        </form>
    </div>

    </main>

    <?php 

include 'includes/footer.php';

?>