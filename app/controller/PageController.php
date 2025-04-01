<?php 


class PageController{

    //instancia del controlador usuario
    private $usuarioController;

    public function __construct() {
        $this->usuarioController = new UsuarioController();
    }

    public function home(){
        if(!isset($_SESSION)){
            session_start();
        }

         if (isset($_SESSION["login"]) && $_SESSION["login"] == true) {
            
            
            //Si el usuario está logueado, redirige a la página de inicio
            header("Location: /index");
            
        } 

     
        
        require_once __DIR__ . "/../view/login.php";
        
    }
    
    /**
     * Recupera los datos del formulario de login y llama al controlador usuario para autenticar los datos
     */
    public function login(){
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $_SESSION["try_username"] = $username;

            $login = $this->usuarioController->login($username, $password);

            if ($login === true) {
                $_SESSION["login"] = true;
               header("Location: /index");
            }else{
                
               header("Location: /home");
            } 

        }

    }

    /**
     * Cierra la sesión y redirige a home
     */
    public function logout(){
            session_unset();
            session_destroy();
            header("Location: /home");
            exit;
        
    }
}

?>