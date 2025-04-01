<?php 
    //si la sesión no se ha iniciado, redirige a home
    if (!isset($_SESSION["login"])) {
        header("Location: /home");
        exit;
    } 

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarRental App</title>
    <link rel="stylesheet" href="/public/css/styles.css">
    
</head>
<body>
    
    
    <header class="header">


        <div class="header-barra" <?php  ?>>
            <div class="header-logo">
                <h1><a href="/index">Car<span class="bold">Rental</span></a></h1>
            </div>

            <div class="user-login">
                <p>Bienvenido, <?php echo $_SESSION["nombre_empleado"] ?></p>
                <form method="POST" action='/logout'>
                    <input class="boton boton-login" type="submit" name="logout" value="Desconectar">
                </form>
            </div>
        </div>

    </header>

    <div class="wrapper">
        <div class="sidebar">
            <h2>Navegacion</h2>
            <ul>
                <li><a href="/reserva/nueva">Nueva Reserva</a></li>
                <li><a href="/reserva/buscar">Buscar Reserva</a></li>
                <li><a href="/reserva/devolucion">Devolución</a></li>
                <li><a href="/flota">Ver flota</a></li>
                <li><a href="/tarifas/lista">Ver tarifas</a></li>
                <li><a href="/categorias/lista">Ver categorías</a></li>

                <?php if ($_SESSION['tipo_usuario'] == 'Responsable'): ?>
                <li><a href="/flota/nuevo">Nuevo Vehículo</a></li>
                <li><a href="/tarifas/nueva">Nueva tarifa</a></li>
                <li><a href="/categorias/nueva">Nueva categoría</a></li>
                <li><a href="/usuario/nuevo">Nuevo Usuario</a></li>
                <li><a href="/usuario/lista">Ver Usuarios</a></li>
                <?php endif; ?>
                
                
                
                
            </ul>
        </div>
    </div>