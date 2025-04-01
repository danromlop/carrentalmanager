<?php

include_once __DIR__ . '/../model/Tarifa.php';

class TarifaController{
    //instancia el modelo
    private $tarifaModel;

    public function __construct(){
        $this->tarifaModel = new Tarifa();
    }

    /**
     * Obtiene las tarifas y redirige al usuario al listado de tarifas
     */
    public function tarifas(){
        $tarifas = $this->tarifaModel->getTarifas();
        include __DIR__ . "/../view/tarifas.php";
    }

    /**
     * Redirige al usuario al formulario de creación de tarifas
     */
    public function nuevo(){

        include __DIR__ . "/../view/creartarifa.php";
    }

    /**
     * Llama al modelo para crear una tarifa con los datos del formulario
     */
    public function crearTarifa(){
        $errores = [];

        $codigoTarifa = $_POST["codigo_tarifa"];
        $nombreTarifa = $_POST["nombre_tarifa"];
        $precioDiario = $_POST["precio_diario"];
        $eliminado = 0;

        // Filtros de error
        if(empty($codigoTarifa) || empty($nombreTarifa) || empty($precioDiario)){
            array_push($errores, "Completa todos los campos");
        }

        $tarifaEditar = $this->tarifaModel->getTarifaPorCodigo($codigoTarifa);

        if($tarifaEditar){
            array_push($errores, "Ese código ya existe");
        }

        if (!empty($errores)) {
                
            $_SESSION["errores_crearT"] = $errores;
            header("Location: /tarifas/nueva"); 
            exit; 
        
        }else{
            $nuevaTarifa = $this->tarifaModel->crearTarifa($codigoTarifa, $nombreTarifa, $precioDiario, $eliminado);
            if(!$nuevaTarifa){
                echo "Error al crear nueva tarifa";
                exit;
            }else{
                $_SESSION["success_crearT"] = "Tarifa con código " . $codigoTarifa . " creada con éxito.";
                header("Location: /tarifas/lista");
            }

        }
    }

    /**
     * Realiza el borrado lógico de la tarifa seleccionada
     */
    public function eliminar(){
        $idTarifa = $_POST["id_tarifa"];

        $tarifaEliminada = $this->tarifaModel->eliminarTarifa($idTarifa);
        if(!$tarifaEliminada){
            echo "Error al eliminar tarifa";
            exit;
        }else{
            $_SESSION['success_crearT'] = "Tarifa eliminada correctamente.";
            header("Location: /tarifas/lista");
        }

    }

    /**
     * Recupera los datos de la tarifa seleccionada y redirige al usuario al formulario de edición
     */
    public function editar(){

        if(isset($_POST["id_tarifa"])){
            $codigoTarifa = $_POST["id_tarifa"];
        }else{
            $codigoTarifa = $_SESSION["form_data_t"]["id_tarifa"];
        }

        $tarifa = $this->tarifaModel->getTarifaPorId($codigoTarifa);
        if(!$tarifa){
            echo "Error al get tarifa";
            exit;
        }

   
        
        include __DIR__ . "/../view/updatetarifa.php";
    }

    /**
     * Llama al modelo para editar la tarifa con los datos del formulario
     */
    public function editarTarifa(){
        $errores = [];
        $_SESSION["form_data_t"] = $_POST;

        
        $idTarifa = $_POST["id_tarifa"];
        $codigoTarifa = $_POST["codigo_tarifa"];
        $nombreTarifa = $_POST["nombre_tarifa"];
        $precioDiario = $_POST["precio_diario"];


        if(empty($codigoTarifa) || empty($nombreTarifa) || empty($precioDiario)){
            array_push($errores, "Completa todos los campos");
        }

        $tarifaEditar = $this->tarifaModel->getTarifaPorCodigo($codigoTarifa);

        
        if($tarifaEditar && $tarifaEditar["id"] != $idTarifa){
            array_push($errores, "Ese código ya existe");
        }

        if (!empty($errores)) {
                
            $_SESSION["errores_crearT"] = $errores;
            header("Location: /tarifas/editar"); 
            exit; 
        
        }else{
            $editarTarifa = $this->tarifaModel->editarTarifa($codigoTarifa, $nombreTarifa, $precioDiario, $idTarifa);
            if(!$editarTarifa){
                echo "Error al editar nueva tarifa";
                exit;
            }else{
                $_SESSION["success_crearT"] = "Tarifa con código " . $codigoTarifa . " editada con éxito.";
                header("Location: /tarifas/lista");
            }

        }
    }
}
