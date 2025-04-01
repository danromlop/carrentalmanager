<?php

include_once __DIR__ . '/../model/Reserva.php';
include_once __DIR__ . '/../model/Cliente.php';
include_once __DIR__ . '/../model/Categoria.php';
include_once __DIR__ . '/../model/Tarifa.php';
include_once __DIR__ . '/../model/Vehiculo.php';

class ReservaController{
    //instanciamos los modelos necesarios
    private $reservasModel;
    private $categoriaModel;
    private $tarifasModel;
    private $vehiculoModel;

    public function __construct(){
        $this->reservasModel = new Reserva();
        $this->categoriaModel = new Categoria();
        $this->tarifasModel = new Tarifa();
        $this->vehiculoModel = new Vehiculo();
    }

    /**
     * Recupera las reservas de hoy y mañana y carga la vista 
     */
    public function lista(){
        

        $reservas = $this->reservasModel->getLista();
     
        //formateamos fecha de cada reserva para mostrarlas
        $reservas = array_map(function($reserva) {
            $reserva['fecha_inicio'] = date('d/m/Y', strtotime($reserva['fecha_inicio']));
            $reserva['fecha_fin'] = date('d/m/Y', strtotime($reserva['fecha_fin']));
            return $reserva;
        }, $reservas);

        //obtenemos la fecha actual y de mañana
        $hoy = date('d/m/Y');
        $mañana = date('d/m/Y', strtotime('+1 day'));

        $reservasHoy = [];
        $reservasFuturas = [];

        foreach ($reservas as $reserva) {
            if($reserva['estado'] != "reservada"){
                continue;
            }
            if ($reserva['fecha_inicio'] == $hoy) {
                $reservasHoy[] = $reserva;
            } elseif ($reserva['fecha_inicio'] == $mañana) {
                $reservasFuturas[] = $reserva;
            }
        }

        

        include __DIR__ . "/../view/lista.php";
    }


    /**
     * Redirige al usuario a la pantalla de Nueva Reserva, cargando la información de categorías y tarifas para el formulario
     */
    public function nueva(){
        //Mantiene la información introducida en caso de error, evitando tener que reintroducir todos los datos
        //Limpiamos variable de sesion en caso de que se esté creando desde 0
        $ruta_anterior = $_SERVER['HTTP_REFERER'] ?? 'No hay ruta anterior';

        //Si la ruta anterior no es la misma, no carga datos de formulario introducido anteriormente
        if(!strpos($ruta_anterior, '/reserva/nueva')){
            unset($_SESSION['form_data']); 
        }

        $categorias = $this->categoriaModel->getCategorias();
        $tarifas = $this->tarifasModel->getTarifas();
        
        


        require_once __DIR__ . '/../view/nueva.php';
        unset($_SESSION['form_data']); 
    }

    /**
     * Obtiene los datos del formulario y llama al modelo para crear una reserva, o editarla si existe un ID.
     */
    public function crearReserva(){
        $clienteModel = new Cliente();


        $errores = [];
        
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            //Almacena los datos del formulario en caso de que haya que reintroducirlos por un error
            $_SESSION['form_data'] = $_POST;
            $_SESSION["prueba"] = "prueba";          

            //Datos del cliente
            $dni = $_POST['dni'];
            $nombre = $_POST['nombre'];
            $apellidos = $_POST['apellidos'];
            $fecha_nacimiento = $_POST['fecha_nacimiento']; 
            $numero_carnet_conducir = $_POST['carnet'];
            $fecha_expedicion = $_POST['fecha_expedicion'];
            $fecha_caducidad = $_POST['fecha_caducidad']; 
            $direccion = $_POST['direccion'];

            //Datos de la reserva
            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_fin = $_POST['fecha_fin'];
            $categoria = $_POST['grupo_vehiculo'];
            $codigoTarifa = $_POST['tarifa'];

            //verifica si hay ID, identificando como edición y no creación
            $reserva_id = isset($_POST['reserva_id']) ? $_POST['reserva_id'] : null;
           

            //valida que las celdas no estén vacías
                if (empty($fecha_inicio) || empty($fecha_fin) || empty($dni) || empty($nombre) || empty($apellidos) || empty($fecha_expedicion) || empty($fecha_caducidad) || empty($direccion)|| empty($numero_carnet_conducir)) {
                    array_push($errores, "Por favor, complete todos los campos requeridos");
                    
                }
          
            //usuario que realiza la reserva 
            $idUsuario = $_SESSION['id'];

            // VALIDACIÓN DE DATOS

            // Valida que la fecha de inicio no pueda ser anterior a la fecha actual
            $fecha_actual = date('Y-m-d');
            if (strtotime($fecha_inicio) < strtotime($fecha_actual)) {
                array_push($errores, "La fecha de inicio no puede ser anterior a la fecha actual.");
                
            }
            // Valida que el cliente no sea menor de 18 años
            $fecha_nacimiento_dt = new DateTime($fecha_nacimiento);
            $edad = (new DateTime())->diff($fecha_nacimiento_dt)->y; // Calcula la edad en años
            if ($edad < 18) {
                array_push($errores, "El cliente debe ser mayor de 18 años.");
            }

            // Valida que la fecha de expedición del carnet no pueda ser antes de que el cliente cumpla 18 años
            $fecha_cumple_18 = (new DateTime($fecha_nacimiento))->modify('+18 years');
            $fecha_expedicion_dt = new DateTime($fecha_expedicion);

            if ($fecha_expedicion > $fecha_actual) {
                array_push($errores, "La fecha de expedición del carnet no puede ser a futuro.");
            }


            // Valida que la fecha de caducidad del carnet no sea inferior a la fecha actual
            if (strtotime($fecha_caducidad) < strtotime($fecha_actual)) {
                array_push($errores, "La fecha de caducidad del carnet no puede ser inferior a la fecha actual.");
            }
            // Valida que la fecha de fin no sea menor que la fecha de inicio
            $fecha_inicio_dt = new DateTime($fecha_inicio);
            $fecha_fin_dt = new DateTime($fecha_fin);
            if ($fecha_fin_dt < $fecha_inicio_dt) {
                array_push($errores, "La fecha de fin no puede ser anterior a la fecha de inicio.");
            }
            // validar formato DNI
            if(!preg_match('/^\d{8}[A-Za-z]$/', $dni)){
                array_push($errores, "El formato del DNI no es válido");
            }

            //Redirección en caso de error
            if (!empty($errores)) {
                if($reserva_id){
                    $_SESSION["errores_crear"] = $errores;
                    header("Location: /reserva/editar/" . $reserva_id); 
                    exit; 
                }
                
                $_SESSION["errores_crear"] = $errores;
                header("Location: /reserva/nueva"); 
                exit; 
            
            }

            if(empty($errores)){
                 // validar si el cliente con el DNI ya existe. Si existe, lo actualiza
                 $clienteExiste = $clienteModel->getClienteByDNI($dni);
                 $idClienteExiste = $clienteExiste['id'];
                if ($clienteExiste) {
                    
                    $clienteYaExistente = $clienteModel->actualizarCliente($idClienteExiste, $dni, $nombre, $apellidos, $fecha_nacimiento, $numero_carnet_conducir, $fecha_expedicion, $fecha_caducidad, $direccion);
                    $_SESSION['success_crear_existente'] = "Se ha actualizado la información del cliente ya existente con DNI " . $dni;

                    $idCliente = $clienteExiste["id"];
                }else{
                    $idCliente = $clienteModel->nuevoCliente($dni, $nombre, $apellidos, $fecha_nacimiento, $numero_carnet_conducir, $fecha_expedicion, $fecha_caducidad, $direccion);
                }
                try{
                    //Crear o editar reserva
                    if ($reserva_id) {
                        $reservaActualizada = $this->reservasModel->updateReserva($reserva_id, $fecha_inicio, $fecha_fin, $idCliente, $idUsuario, $categoria, $codigoTarifa);
                        $_SESSION['success_crear'] = "Reserva actualizada correctamente con ID: $reserva_id";
                        unset($_SESSION['form_data']); 
                        header("Location: /reserva/editar/" . $reserva_id);
                        exit;
                    } else{
                        $reservaNueva = $this->reservasModel->insertNuevaReserva($fecha_inicio, $fecha_fin, $idCliente, $idUsuario, $categoria, $codigoTarifa);
                        $_SESSION['success_crear'] = "Reserva creada correctamente con ID: $reservaNueva";
                        unset($_SESSION['form_data']);
                        header("Location: /reserva/nueva");
                        exit;
                    }
                        
                    
    
                }catch (Exception $e) {
                    
                    echo "Error al insertar la reserva: " . $e->getMessage();
                    $_SESSION['error_crear'] = "Error al insertar la reserva: " . $e->getMessage();
        
                    
                }
            }
            
        }

        
        
    }


    /**
     * Obtiene el ID introducido en la búsqueda y llama al modelo para recuperarla
     */
    public function buscar(){
        $_SESSION['errores_crear'] = [];
        $hoy = date('Y-m-d');
        $mostrarBuscar = false;
        $error = "";
        if(isset($_POST['id_reserva'])){
            $id_reserva = $_POST['id_reserva'];
           
            
            $reserva = $this->reservasModel->getReserva($id_reserva); 
            if(!$reserva){
                if(!$id_reserva){
                    $error = "";
                } else{
                    $error = "Error - Reserva con ID: ". $id_reserva ." no encontrada.";
                }
               
                
            }else{
                $mostrarBuscar = true;
            }

        }

        require_once __DIR__ . '/../view/buscar.php';
    }

    /**
     * Obtiene la matrícula introducida en la devolución y llama a los modelos correspondientes para recuperar su información
     */
    public function buscarDevolucion(){
        $_SESSION['errores_crear'] = [];
        $mostrarBuscar = false;
        $error = null;
        if(isset($_POST['matricula'])){
            $matricula = $_POST['matricula'];
           
            $vehiculo = $this->vehiculoModel->getVehiculoPorMatricula($matricula);
            $idVehiculo = $vehiculo["id"];
            
            $reserva = $this->reservasModel->getReservaByMatricula($idVehiculo); 
            if(!$reserva){
                if(!$matricula){
                    $error = null;
                } else{
                    $error = "Error - Reserva con matrícula asignada: ". $matricula ." no encontrada o no se encuentra en alquiler." ;
                }
                
            }else{
                header("Location: /reserva/devolverVehiculo/" . $reserva["id_reserva"]);
                        exit; 
            }

        }

        require_once __DIR__ . '/../view/devolucion.php';
    }

    /**
     * Llama a los modelos para recuperar la información de la reserva y muestra el formulario de devolución
     */
    public function devolverVehiculo($params){

        $reserva = $this->reservasModel->getReserva($params["id"]);
        $tarifa = $this->tarifasModel->getTarifaPorCodigo($reserva["id_tarifa"]);

        //Días de alquiler
        $fechaIni = new DateTime($reserva["fecha_inicio"]);
        $fechaFin = new DateTime($reserva["fecha_fin"]);
        $dif = $fechaIni->diff($fechaFin);
        $diasAlquiler = $dif->days;

        $precioFinal = $tarifa["precio_diario"] * $diasAlquiler;
        


        
        require_once __DIR__ . '/../view/devolucionVehiculo.php';
    }

    /**
     * Obtiene los datos introducidos de kilómetros, llama al modelo para actualizar la devolución y carga la vista de confirmación
     */
    public function realizarDevolucion($params){
        $_SESSION['error_devolver'] = "";

        $idReserva = $params["id"];
        $kilometrosEntrada = $_POST["kilometrosEntrada"];
        $kilometrosSalida = $_POST["kilometrosSalida"];
        $matricula = $_POST["matricula"];

        if($kilometrosEntrada <= $kilometrosSalida){
            $_SESSION['error_devolver'] = "Error - los kilómetros de entrada no pueden ser menor o igual que los de salida";
            header("Location: /reserva/devolverVehiculo/" . $idReserva);
            exit;
        }
        if(!$kilometrosEntrada){
            $_SESSION['error_devolver'] = "Introduce los kilómetros de entrada";
            header("Location: /reserva/devolverVehiculo/" . $idReserva);
            exit;
        }

        $reservaFinalizada = $this->reservasModel->devolucionReserva($idReserva, $kilometrosEntrada); 
        if(!$reservaFinalizada){
            $_SESSION['error_devolver'] = "Error al finalizar la reserva";
            header("Location: /reserva/devolverVehiculo/" . $idReserva);
            exit;
        }
        $devolverVehiculo = $this->vehiculoModel->devolverVehiculo($matricula);
        if(!$devolverVehiculo){
            $_SESSION['error_devolver'] = "Error al actualizar el estado del vehículo";
            header("Location: /reserva/devolverVehiculo/" . $idReserva);
            exit;
        }else{
            $this->vehiculoModel->updateKilometrosVehiculo($matricula,$kilometrosEntrada);
        }

        
        $_SESSION['success_devolver'] = "Devolución realizada correctamente con reserva ID: $idReserva y vehículo $matricula";
        
        $_SESSION['error_devolver'] = null;
        
        $reserva = $this->reservasModel->getReserva($idReserva);

        require_once __DIR__ . '/../view/devolucionVehiculoOk.php';
    }


    /**
     * Recupera la información de la reserva y redirige al formulario de editar
     */
    public function editar($params){

       $reserva = $this->reservasModel->getReserva($params["id"]);
       $categorias = $this->categoriaModel->getCategorias();
       $tarifas = $this->tarifasModel->getTarifas();

        require_once __DIR__ . '/../view/actualizar.php';
    }

    /**
     * Realiza el borrado lógico de la reserva a través del ID recuperado del formulario hidden.
     */
    public function eliminar(){
        
        $idReserva = $_POST["id_reserva"];

        $reservaEliminada = $this->reservasModel->eliminarReserva($idReserva);
        if(!$reservaEliminada){
            echo "Error al eliminar reserva";
            exit;
        }else{
            $_SESSION['success_eliminarR'] = "Reserva con ID " . $idReserva . " elimninada correctamente";
        }

        header("Location: /reserva/buscar");
    }

    /**
     * Recupera la información de la reserva por ID y redirige al formulario de alquiler
     */
    public function alquilar($params){
        $reserva = $this->reservasModel->getReserva($params["id"]);
        $categorias = $this->categoriaModel->getCategorias();
        $tarifas = $this->tarifasModel->getTarifas();


        require_once __DIR__ . '/../view/alquilar.php';
    }

    /**
     * Marca la reserva como alquier asignándole el vehículo introducido por formulario, y redirige a la pantalla de confirmación
     */
    public function alquilarVehiculo(){
        $clienteModel = new Cliente();
        
        $errores = [];
        
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $_SESSION['form_data'] = $_POST; 
            $_SESSION["prueba"] = "prueba";          

            //datos del cliente
            $idCliente = $_POST['id_cliente'];
            $dni = $_POST['dni'];
            $nombre = $_POST['nombre'];
            $apellidos = $_POST['apellidos'];
            $fecha_nacimiento = $_POST['fecha_nacimiento']; 
            $numero_carnet_conducir = $_POST['carnet'];
            $fecha_expedicion = $_POST['fecha_expedicion'];
            $fecha_caducidad = $_POST['fecha_caducidad']; 
            $direccion = $_POST['direccion'];

            //datos vehiculo
            $matricula = $_POST['matricula'];

            //id reserva
            $reserva_id = isset($_POST['reserva_id']) ? $_POST['reserva_id'] : null;
          

            // Valida que las fechas no estén vacías
            if (empty($dni) || empty($nombre) || empty($apellidos) || empty($fecha_expedicion) || empty($fecha_caducidad) || empty($direccion) || empty($matricula)) {
                array_push($errores, "Por favor, complete todos los campos requeridos");
            }

            // VALIDACIÓN DE DATOS
            
            // Valida que el cliente no sea menor de 18 años
            $fecha_nacimiento_dt = new DateTime($fecha_nacimiento);
            $edad = (new DateTime())->diff($fecha_nacimiento_dt)->y; // Calcula la edad en años
            if ($edad < 18) {
                array_push($errores, "El cliente debe ser mayor de 18 años.");
            }

            // Valida que la fecha de expedición del carnet no pueda ser antes de que el cliente cumpla 18 años
            $fecha_cumple_18 = (new DateTime($fecha_nacimiento))->modify('+18 years');
            $fecha_expedicion_dt = new DateTime($fecha_expedicion);

            if ($fecha_expedicion_dt < $fecha_cumple_18) {
                array_push($errores, "La fecha de expedición del carnet no puede ser antes de que el cliente cumpliera 18 años.");
            }
            // Valida que la fecha de caducidad del carnet no sea inferior a la fecha actual
            $fecha_actual = date('Y-m-d');
            if (strtotime($fecha_caducidad) < strtotime($fecha_actual)) {
                array_push($errores, "La fecha de caducidad del carnet no puede ser inferior a la fecha actual.");
            }

            if (!empty($errores)) {
                
                $_SESSION["errores_crear"] = $errores;
                header("Location: /reserva/alquilar/" . $reserva_id ); 
                exit;
            }

            if(empty($errores)){ 
                $clienteYaExistente = $clienteModel->actualizarCliente($idCliente, $dni, $nombre, $apellidos, $fecha_nacimiento, $numero_carnet_conducir, $fecha_expedicion, $fecha_caducidad, $direccion);
                $_SESSION['success_crear_existente'] = "Se ha actualizado la información del cliente ya existente con DNI " . $dni;

                //primero obtiene la matrícula que exista, si no, devuelve un error
                $vehiculoAsignado = $this->vehiculoModel->getVehiculoPorMatricula($matricula);
                $kilometrosSalida = $vehiculoAsignado["kilometros_actuales"];
                $idVehiculoAsignado = $vehiculoAsignado["id"];
               
                if(!$vehiculoAsignado || $vehiculoAsignado["alquilado"] == true){
                    array_push($errores, "La matrícula seleccionada no existe o no está disponible " . $idVehiculoAsignado);
                    $_SESSION["errores_crear"] = $errores;
                    header("Location: /reserva/alquilar/" . $reserva_id);
                    exit;
                }

                //marcar vehiculo como alquilado
                $vAlquilado = $this->vehiculoModel->alquilarVehiculo($matricula);
                if(!$vAlquilado){
                    array_push($errores, "Error al actualizar el estado del vehículo");
                    $_SESSION["errores_crear"] = $errores;
                    header("Location: /reserva/alquilar/" . $reserva_id);
                    exit;
                }

                //alquilar reserva
                $alquiler = $this->reservasModel->alquilarReserva($reserva_id, $idVehiculoAsignado, $kilometrosSalida);
                $_SESSION['success_crear_existente'] = "Reserva alquilada con id " . $reserva_id;

                if($alquiler){
                    $reserva = $this->reservasModel->getReserva($reserva_id);
                    $_SESSION['success_crear_existente'] = "¡En alquiler! Se ha asignado el vehículo con matrícula " . $matricula . " a la reserva nº " . $reserva_id;

                    require_once __DIR__ . '/../view/alquilarReserva.php';
                    exit;

                }else{
                    array_push($errores, "Error al ejecutar alquilerReserva");
                    $_SESSION["errores_crear"] = $errores;
                }
                
            }
            
        }

    }

    /**
     * Redirige al buscador de matrícula para devolución
     */
    public function devolucion(){
        require_once __DIR__ . '/../view/devolucion.php';
    }

    
}