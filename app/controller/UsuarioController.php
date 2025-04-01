<?php

include_once __DIR__ . '/../model/Usuario.php';



class UsuarioController {
    //instancia el modelo
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    /**
     * Recupera la lista de usuarios en activo del modelo y redirige al usuario a la tabla de usuarios
     */
    public function listaUsuarios() {
        $usuarios = $this->usuarioModel->obtenerUsuarios();
        require_once __DIR__ . '/../view/usuarios.php';
        //include '../view/usuarios.php';
    }

    /**
     * Redirige al usuario al formulario de creación de usuario
     */
    public function nuevo(){

        require_once __DIR__ . '/../view/crearusuario.php';
    }

    /**
     * Crea el usuario a través del modelo con los datos del formulario
     */
    public function crearUsuario(){

        $errores = [];

        $nombreUsuario = $_POST["nombre_usuario"];
        $tipoUsuario = $_POST["tipo_usuario"];
        $nombreEmpleado = $_POST["nombre_empleado"];
        $contrasena = $_POST["contrasena"];

        if(empty($nombreUsuario) || empty($tipoUsuario) ||empty($nombreEmpleado) ||empty($contrasena)){
            array_push($errores, "Completa todos los campos");
        }

        $usuarioValidacion = $this->usuarioModel->getUsuarioPorNombre($nombreUsuario);

        if($usuarioValidacion && !$usuarioValidacion["eliminado"]){
            array_push($errores, "Ese usuario ya existe");
        }
         
        if (!empty($errores)) {
                
            $_SESSION["errores_crearU"] = $errores;
            header("Location: /usuario/nuevo"); 
            exit; 
        
        }else{
            $nuevoUsuario = $this->usuarioModel->crearUsuario($nombreUsuario, $contrasena, $tipoUsuario, $nombreEmpleado);
            if(!$nuevoUsuario){
                echo "Error al crear usuario";
                exit;
            }else{
                $_SESSION["success_crearU"] = "Usuario con nombre " . $nombreUsuario . " creado con éxito.";
                header("Location: /usuario/lista");
            }

        }
    }


    /**
     * Redirige al usuario al formulario de edición del usuario seleccionado, recuperando su información
     */
    public function editar(){
        
        if(isset($_POST["nombre_usuario"])){
            $nombreUsuario = $_POST["nombre_usuario"];
        }else{
            $nombreUsuario = $_SESSION["form_data_t"]["nombreUsuarioEditar"];
        }

        $usuario = $this->usuarioModel->getUsuarioPorNombre($nombreUsuario);
        if(!$usuario){
            echo "Error al get usuario";
            exit;
        }
        

        require_once __DIR__ . '/../view/updateusuarios.php';
    }

    /**
     * Edita el usuario con los datos del formulario
     */
    public function editarUsuario(){

        $errores = [];

        $_SESSION["form_data_t"] = $_POST;

        $nombreUsuario = $_POST["nombre_usuario"];
        $tipoUsuario = $_POST["tipo_usuario"];
        $nombreEmpleado = $_POST["nombre_empleado"];
        $contrasena = $_POST["contrasena"];
        $nombreUsuarioEditar = $_POST["nombreUsuarioEditar"];

        if(empty($nombreUsuario) || empty($tipoUsuario) ||empty($nombreEmpleado) ||empty($contrasena)){
            array_push($errores, "Completa todos los campos");
        }

        $usuarioValidacion = $this->usuarioModel->getUsuarioPorNombre($nombreUsuario);

        if($usuarioValidacion && !$usuarioValidacion["eliminado"] && $nombreUsuarioEditar != $usuarioValidacion["nombre_usuario"]){
            array_push($errores, "Ese usuario ya existe");
        }
         
        if (!empty($errores)) {
                
            $_SESSION["errores_crearU"] = $errores;
            header("Location: /usuario/editar"); 
            exit; 
        
        }else{
            $nuevoUsuario = $this->usuarioModel->editarUsuario($nombreUsuario, $contrasena, $tipoUsuario, $nombreEmpleado, $nombreUsuarioEditar);
            if(!$nuevoUsuario){
                echo "Error al editar usuario";
                exit;
            }else{
                $_SESSION["success_crearU"] = "Usuario con nombre " . $nombreUsuario . " editado con éxito.";
                header("Location: /usuario/lista");
            }

        }

    }

    /**
     * Realiza el borrado lógico del usuario
     */
    public function eliminar(){
        $idUsuario = $_POST["id_usuario"];
        $usuarioEliminar = $this->usuarioModel->getUsuarioPorId($idUsuario);
        $nombreUsuario = $usuarioEliminar["nombre_usuario"];

        $usuarioEliminado = $this->usuarioModel->eliminarUsuario($idUsuario);
        if(!$usuarioEliminado){
            echo "Error al eliminar usuario";
            exit;
        }else{
            $_SESSION['success_crearU'] = "Usuario con nombre " . $nombreUsuario . " eliminado correctamente.";
            header("Location: /usuario/lista");
        }
    }

    /**
     * Autentica las credenciales del usuario 
     */
    public function login($usuario, $password){
        $auth = $this->usuarioModel->autenticarUsuario($usuario, $password);
        if($auth == true){
            $_SESSION["login"] = true;
            return true;
        }else{
           return false;
        }

    }


}





?>
