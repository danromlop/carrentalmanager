
<?php 

include 'includes/header.php';

?>
    <main class="main">

        <h2>Alquiler en curso</h2>

      
     <hr>


     <div class="w60">

     <?php 
         if (isset($_SESSION['success_crear'])) {
            echo '<p class="success">' . htmlspecialchars($_SESSION['success_crear']) . '</p>'; 
            unset($_SESSION['success_crear']); 
        }
        if (isset($_SESSION['success_crear_existente'])) {
            echo '<p class="success">' . htmlspecialchars($_SESSION['success_crear_existente']) . '</p>'; 
            unset($_SESSION['success_crear_existente']); 
        }
     
     ?> 
         
            
         
                <div class="info-reserva-container">
            
                    <div class="nested-grid">
                        <div>
                            <span>Nombre:</span>
                            <p><?php echo $reserva['nombre_cliente']; ?></p>
                        </div>
                        <div>
                            <span>Apellidos:</span>
                            <p><?php echo $reserva['apellidos']; ?></p>
                        </div>
                    </div>
            
                    <div>
                        <span>DNI:</span>
                        <p><?php echo $reserva['dni_cliente']; ?></p>
                    </div>
                    <div>
                        <span>Nº Carnet:</span>
                        <p><?php echo $reserva['numero_carnet_conducir']; ?></p>
                    </div>
            
            
                    <div>
                        <span>Fecha nacimiento:</span>
                        <p><?php echo $reserva['fecha_nacimiento']; ?></p>
                    </div>
            
                    <div class="nested-grid">
                        <div>
                            <span>Fecha exp:</span>
                            <p><?php echo $reserva['fecha_expedicion']; ?></p>
                        </div>
            
                        <div>
                            <span>Fecha cad:</span>
                            <p><?php echo $reserva['fecha_caducidad']; ?></p>
                        </div>
                    </div>
                    <div>
                        <span>Dirección:</span>
                        <p><?php echo $reserva['direccion']; ?></p>
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
                        <p><?php echo $reserva['matricula']; ?></p>
                    </div>
            
                </div>
            
                
            
               
     </div>

    </main>

    <?php 

include 'includes/footer.php';

?>