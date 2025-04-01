<?php 

require_once __DIR__ . '/database.php'; 

class Usuario{

    //conexión con bbdd
    private $db;
    public function __construct(){
       

        $database = new Database();
        
        $this->db = $database->getDatabase();
      
    }

    /**
     * Obtiene todos los usuarios
     */
    public function obtenerUsuarios(){
        $sql = "SELECT * FROM usuarios WHERE eliminado = FALSE;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();

        if($result->num_rows > 0){
            return $result->fetch_all(MYSQLI_ASSOC); // Devuelves un array
              
            
        } else{
            return [];
        }
    }

    /**
     * Recupera un usuario por su nombre de usuario
     */
    public function getUsuarioPorNombre($nombreUsuario){
        $sql = "SELECT * FROM usuarios WHERE nombre_usuario = ?;";
        $stmt = $this->db->prepare($sql);
        if($stmt){
            $stmt->bind_param("s",$nombreUsuario);
            $stmt->execute();
            $res = $stmt->get_result();
            if($res->num_rows > 0){
                return $res->fetch_assoc();
            }else{
                return false;
            }
        }else{
            return false;
        }

    }

    /**
     * Obtiene un usuario por ID
     */
    public function getUsuarioPorId($idUsuario){
        $sql = "SELECT * FROM usuarios WHERE id_usuario = ?;";
        $stmt = $this->db->prepare($sql);
        if($stmt){
            $stmt->bind_param("i",$idUsuario);
            $stmt->execute();
            $res = $stmt->get_result();
            if($res->num_rows > 0){
                return $res->fetch_assoc();
            }else{
                return false;
            }
        }else{
            return false;
        }

    }

    /**
     * Crea un usuario
     */
    public function crearUsuario($nombreUsuario, $password, $tipoUsuario, $nombreEmpleado){

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nombre_usuario, contrasena, tipo_usuario, nombre_empleado) VALUES (?, ?, ?,?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssss', $nombreUsuario, $hashedPassword, $tipoUsuario,$nombreEmpleado);

        if ($stmt->execute()) {
            $stmt->close();
            return true; 
        } else {
            echo "Error en la consulta: " . $stmt->error; 
            $stmt->close();
            return false;  
        }
    }

    /**
     * Edita un usuario existente
     */
    public function editarUsuario($nombreUsuario, $password, $tipoUsuario, $nombreEmpleado, $nombreUsuarioEditar){

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("UPDATE usuarios SET
            nombre_usuario = ?,
            contrasena = ?,
            tipo_usuario = ?,
            nombre_empleado = ?
            WHERE nombre_usuario = ?");

            $stmt->bind_param('sssss', $nombreUsuario, $hashedPassword, $tipoUsuario, $nombreEmpleado, $nombreUsuarioEditar);

            

            if ($stmt->execute() && $stmt->affected_rows > 0) {
                $stmt->close();
                
                return true; 
            } else {
                echo "Error en la consulta: " . $stmt->error; 
                $stmt->close();
                return false;  
            }

    }

    /**
     * Elimina un usuario actualizando su campo eliminado a True (borrado lógico)
     */
    public function eliminarUsuario($nombreUsuario){
        $stmt = $this->db->prepare("UPDATE usuarios SET
                        eliminado = TRUE
                        WHERE id_usuario = ?");
        $stmt->bind_param("i", $nombreUsuario);

        if ($stmt->execute()) {
            $stmt->close();
            return true; 
        } else {
            echo "Error en la consulta: " . $stmt->error; 
            $stmt->close();
            return false;  
        }
    }

    /**
     * Autentica la información del usuario con la BBDD
     */
    public function autenticarUsuario($usuario, $password){
        $sql = "SELECT * FROM usuarios WHERE nombre_usuario = ? AND eliminado = FALSE";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $usuario);
        $msgs = $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        
        var_dump($resultado);


        if ($resultado->num_rows > 0) {
            
            $usuarioLogin = $resultado->fetch_assoc();
            //Verificación contraseña 
            if (password_verify($password, $usuarioLogin['contrasena'])) {
                //Si la contraseña es correcta, llena la variable global de sesión con informaciónd del usuario
                $_SESSION['id'] = $usuarioLogin["id_usuario"];
                $_SESSION['usuario'] = $usuarioLogin['nombre_usuario'];
                $_SESSION['tipo_usuario'] = $usuarioLogin['tipo_usuario'];
                $_SESSION['nombre_empleado'] = $usuarioLogin['nombre_empleado'];
                return true;
            } else {
                echo "contraseña incorrecta";
                //Contraseña incorrecta
                $_SESSION['error_message'] = "Contraseña incorrecta";
                return false;
            }
        } else {
                $_SESSION['error_message'] = "Usuario no encontrado";
                return false;
        }

    }

}



?>