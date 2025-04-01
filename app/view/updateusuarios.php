
<?php 

include 'includes/header.php';

?>

    <main class="main">

        <h2>Editar usuario</h2>


     <hr>

    <div class="w60">
    <?php
        if (isset($_SESSION['errores_crearU'])) {
            foreach ($_SESSION['errores_crearU'] as $error) {
                echo '<p class="error">' . htmlspecialchars($error) . '</p>';
            }
    
        unset($_SESSION['errores_crearU']); 
        }
    ?>
    </div>

    <div class="w60">
        
        <form method="POST" action="/usuario/editarUsuario">
            <div class="info-usuario-container">
        
                <div>
                    <span>Nombre de usuario:</span>
                    <input  type="text" name="nombre_usuario" value="<?php echo $usuario["nombre_usuario"]?>">
                </div>
                <input type="hidden" name="nombreUsuarioEditar" value="<?php echo $usuario["nombre_usuario"]?>">

                <div>
                    <span>Tipo usuario:</span>
                    <select name="tipo_usuario">
                        <option value="Usuario">Usuario</option>
                        <option value="Responsable">Responsable</option>
                    </select>
                </div>

                <div>
                    <span>Nombre y apellidos del empleado:</span>
                    <input type="text" name="nombre_empleado" value="<?php echo $usuario["nombre_empleado"]?>">
                </div>
                <div>
                    <span>Contraseña:</span>
                    <input type="password" name="contrasena">
                </div>

        
            </div>
            <input type="submit" class="boton boton-main" value="Actualizar">
        </form>
    </div>

    </main>

    <?php 

include 'includes/footer.php';

?>