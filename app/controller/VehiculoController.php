<?php

include_once __DIR__ . '/../model/Vehiculo.php';
include_once __DIR__ . '/../model/Tarifa.php';
include_once __DIR__ . '/../model/Categoria.php';

class VehiculoController{

    //instancia los modelos
    private $vehiculoModel;
    private $categoriaModel;

    public function __construct(){
        $this->vehiculoModel = new Vehiculo();
        $this->categoriaModel = new Categoria();
    }

    /**
     * Recupera la flota de vehículos activa y redirige al usuario al listado de flota
     */
    public function flota(){
        $flota = $this->vehiculoModel->getVehiculo();

        include __DIR__ . "/../view/flota.php";
    }

    /**
     * Redirige al usuario al formulario de creación de vehículos
     */
    public function nuevo(){

        $categorias = $this->categoriaModel->getCategorias();

        include __DIR__ . "/../view/crearvehiculo.php";
    }

    /**
     * Crea el vehículo con los datos del formulario
     */
    public function crearVehiculo(){

        $errores = [];
        
        $matricula = $_POST["matricula"];
        $marca = $_POST["marca"];
        $modelo = $_POST["modelo"];
        $kilometrosActuales = $_POST["kilometrosActuales"];
        $idCategoria = $_POST["idCategoria"];
        $alquilado = 0;
        $eliminado = 0;


        if(empty($matricula) || empty($marca) || empty($modelo) || empty($kilometrosActuales) || empty($idCategoria)){
            array_push($errores, "Completa todos los campos");

        };

        if($matricula != null && !preg_match('/^\d{4}[A-Z]{3}$/', $matricula)){
            array_push($errores, "La matrícula no es válida");
        }

        if($this->vehiculoModel->getVehiculoPorMatricula($matricula)){
            array_push($errores, "Matrícula ya existente");
        }

        if (!empty($errores)) {
                
            $_SESSION["errores_crearV"] = $errores;
            header("Location: /flota/nuevo"); 
            exit; 
        
        }else{
            $nuevoVehiculo = $this->vehiculoModel->crearVehiculo($matricula, $marca, $modelo, $idCategoria, $kilometrosActuales, $alquilado, $eliminado);
            if(!$nuevoVehiculo){
                echo "Error al crear nuevo vehiculo";
                exit;
            }else{
                $_SESSION['success_crearV'] = "Vehículo con matrícula " . $matricula . " creado correctamente.";
                header("Location: /flota");
            }
            
        }


    }

    /**
     * Realiza el borrado lógico del vehículo seleccionado
     */
    public function eliminar(){
        $matricula = $_POST["matricula"];

        $vehiculoEliminado = $this->vehiculoModel->eliminarVehiculo($matricula);
        if(!$vehiculoEliminado){
            echo "Error al eliminar vehiculo";
            exit;
        }else{
            $_SESSION['success_crearV'] = "Vehículo con matrícula " . $matricula . " eliminado correctamente.";
        }

        
        header("Location: /flota");

    }

    /**
     * Redirige al usuario al formuulario de edición de vehículos
     */
    public function editar(){

        
        $categorias = $this->categoriaModel->getCategorias();

        //condicional para recuperar información del formulario en caso de error

        if(isset($_POST["id"])){
            $id = $_POST["id"];
        }else{
            $id = $_SESSION["form_data_v"]["id"];
        }

        $vehiculo = $this->vehiculoModel->getVehiculoPorId($id);
        if (!$vehiculo){
            echo "Error al get vehiculo";
            echo $id;
            exit;
        }
        
        
        include __DIR__ . "/../view/actualizarvehiculo.php";
    }

    /**
     * Edita el vehículo con la información del formulario 
     */
    public function editarVehiculo(){
        $errores = [];
        $_SESSION['form_data_v'] = $_POST;
        
        $id = $_POST["id"];
        $matricula = $_POST["matricula"];
        $marca = $_POST["marca"];
        $modelo = $_POST["modelo"];
        $kilometrosActuales = $_POST["kilometrosActuales"];
        $idCategoria = $_POST["id_categoria"];

        if(empty($matricula) || empty($marca) || empty($modelo) || empty($kilometrosActuales) || empty($idCategoria)){
            array_push($errores, "Completa todos los campos");

        };

        if($matricula != null && !preg_match('/^\d{4}[A-Z]{3}$/', $matricula)){
            array_push($errores, "La matrícula no es válida");
        }

        $vehiculoEditar = $this->vehiculoModel->getVehiculoPorMatricula($matricula);

        if($vehiculoEditar && $vehiculoEditar["id"] != $id){
            array_push($errores, "Matrícula ya existente");
        }

        if (!empty($errores)) {
                
            $_SESSION["errores_crearV"] = $errores;
            header("Location: /flota/editar");
            exit; 
        
        }else{
            $nuevoVehiculo = $this->vehiculoModel->editarVehiculo($matricula, $marca, $modelo, $idCategoria, $kilometrosActuales, $id);
            if(!$nuevoVehiculo){
                echo "Error al editar nuevo vehiculo";
                exit;
            }else{
                $_SESSION['success_crearV'] = "Vehículo con matrícula " . $matricula . " editado correctamente.";
                header("Location: /flota");
            }
            
        }

    }
}