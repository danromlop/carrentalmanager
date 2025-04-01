
<?php 

include 'includes/header.php';

?>


    <main class="main">
        
       <h2>Reservas de hoy</h2>

       <div class="reservas-container">
       
       <?php 
       
       if(empty($reservasHoy)){
            echo "<div class=\"reserva\">Sin reservas para hoy</div>";
       }
       
       
       foreach ($reservasHoy as $reserva): ?>

        <div class="reserva reserva-click">
            <a href="/reserva/alquilar/<?php echo $reserva['id_reserva']; ?>">
            
                <p><?php echo $reserva['apellidos_cliente'] . ","; ?> <?php echo "&nbsp; " . $reserva['nombre_cliente'] . " - Nº reserva: " . $reserva["id_reserva"]; ?></p>
                <p>Fecha Inicio: <?php echo $reserva['fecha_inicio']; ?> - Fecha Fin: <?php echo $reserva['fecha_fin']; ?></p>
                <p>Grupo reservado: <?php echo $reserva["id_categoria"] . " - " .$reserva['categoria_vehiculo'] ?></p>
            </a>
        </div>
        <?php endforeach; ?>

       

       </div>

       <h2>Reservas de mañana</h2>
        
       <div class="reservas-container">

       <?php

       if(empty($reservasFuturas)){
            echo "<div class=\"reserva\">No hay reservas para mañana</div>";
       }

        foreach ($reservasFuturas as $reserva): ?>

            <div class="reserva">
                <p><?php echo $reserva['apellidos_cliente'] . ","; ?> <?php echo "&nbsp; " . $reserva['nombre_cliente']. " - Nº reserva: " . $reserva["id_reserva"];  ?></p>
                <p>Fecha Inicio: <?php echo $reserva['fecha_inicio']; ?> - Fecha Fin: <?php echo $reserva['fecha_fin']; ?></p>
                <p>Grupo reservado: <?php echo $reserva["id_categoria"] . " - " .$reserva['categoria_vehiculo'] ?></p>
            </div>

        <?php endforeach; ?>


       </div>

    </main>


    <?php 

include 'includes/footer.php';

?>