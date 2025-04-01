<?php


class Reserva{

    // conexion con bbdd
    private $db;

    public function __construct(){
       
        
        $database = new Database();
        
        $this->db = $database->getDatabase();
      
    }

    /* FUNCIONES CRUD RESERVAS */ 

   /**
    * Recuperar una lista de reservas - JOIN con clientes, vehiculos, y categorías
    */
    public function getLista(){

    //sentencia sql
    $sql = "SELECT 
        reservas.id_reserva,
        reservas.fecha_inicio,
        reservas.fecha_fin,
        reservas.estado,
        clientes.nombre AS nombre_cliente,
        clientes.apellidos AS apellidos_cliente,
        vehiculos.matricula,
        vehiculos.marca,
        vehiculos.modelo,
        categorias.nombre_categoria AS categoria_vehiculo,
        categorias.id_categoria
        FROM 
            reservas
        LEFT JOIN 
            clientes ON reservas.id_cliente = clientes.id
        LEFT JOIN 
            vehiculos ON reservas.id_vehiculo = vehiculos.id
        LEFT JOIN 
            categorias ON reservas.id_categoria = categorias.id"; 

    $resultado = $this->db->query($sql);
    
    if ($resultado->num_rows > 0) {
        
        return $resultado->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }

    }

    /**
     * Obtiene una reserva por ID - JOIN con clientes, vehiculos, tarifas y categorías
     */
    public function getReserva($id){

        $sql = "SELECT 
        r.id_reserva,
        r.fecha_inicio,
        r.fecha_fin,
        r.kilometros_salida,
        r.kilometros_entrada,
        r.estado,
        r.id_cliente,
        r.id_categoria,
        r.id_tarifa,
        v.matricula,
        c.nombre AS nombre_cliente,
        c.dni AS dni_cliente,
        c.apellidos,
        c.numero_carnet_conducir,
        c.fecha_nacimiento,
        c.fecha_expedicion,
        c.fecha_caducidad,
        c.direccion,
        v.marca,
        v.modelo,
        cat.nombre_categoria AS nombre_categoria,
        cat.id_categoria AS id_categoria_coche,
        t.codigo_tarifa
        FROM 
            reservas r
        LEFT JOIN 
            clientes c ON r.id_cliente = c.id
        LEFT JOIN 
            vehiculos v ON r.id_vehiculo = v.id
        LEFT JOIN 
            categorias cat ON r.id_categoria = cat.id
        LEFT JOIN
            tarifas t on r.id_tarifa = t.id
        WHERE 
            r.id_reserva = ?";       
        
        if ($stmt = $this->db->prepare($sql)) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc(); 
        }
       
        return false; 
    
       
    }

    /**
     * Obtiene una reserva por matrícula - JOIN con clientes, vehiculos, tarifas y categorías
     */
    public function getReservaByMatricula($matricula){

        $sql = "SELECT 
        r.id_reserva,
        r.fecha_inicio,
        r.fecha_fin,
        r.kilometros_salida,
        r.kilometros_entrada,
        r.estado,
        r.id_categoria,
        v.matricula,
        c.nombre AS nombre_cliente,
        c.dni AS dni_cliente,
        c.apellidos,
        c.numero_carnet_conducir,
        c.fecha_nacimiento,
        c.fecha_expedicion,
        c.fecha_caducidad,
        c.direccion,
        v.marca,
        v.modelo,
        cat.nombre_categoria AS nombre_categoria,
        cat.id_categoria AS id_categoria_coche,
        t.codigo_tarifa
        FROM 
            reservas r
       LEFT JOIN 
            clientes c ON r.id_cliente = c.id
        LEFT JOIN 
            vehiculos v ON r.id_vehiculo = v.id
        LEFT JOIN 
            categorias cat ON r.id_categoria = cat.id
        LEFT JOIN
            tarifas t on r.id_tarifa = t.id
        WHERE 
            r.id_vehiculo = ? AND r.estado = 'En alquiler'";

        

        if ($stmt = $this->db->prepare($sql)) {
            $stmt->bind_param('s', $matricula);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc(); 
        }
       
        return false; 
    
       
    }

    /**
     * Crea una nueva reserva
     */
    public function insertNuevaReserva($fecha_inicio, $fecha_fin, $dni, $idUsuario, $grupoVehiculo, $codigoTarifa){
     
        $stmt = $this->db->prepare("INSERT INTO reservas (
                    fecha_inicio, 
                    fecha_fin, 
                    id_cliente, 
                    id_usuario, 
                    id_categoria, 
                    id_tarifa, 
                    estado
                ) VALUES (?,?,?,?,?,?,'Reservada');");

        $stmt->bind_param("sssiss", $fecha_inicio, $fecha_fin, $dni, $idUsuario, $grupoVehiculo, $codigoTarifa);

        if ($stmt->execute()) {
            $id_reserva = $this->db->insert_id;
            $stmt->close();
            $this->db->close();
            return $id_reserva; 
        } else {
            echo "Error en la consulta: " . $stmt->error; 
            $stmt->close();
            $this->db->close();
            return false;  
        }
        
    }

    /**
     * Edita una reserva existente por ID
     */
    public function updateReserva($id_reserva, $fecha_inicio, $fecha_fin, $dni, $idUsuario, $grupoVehiculo, $codigoTarifa) {

        $stmt = $this->db->prepare("UPDATE reservas SET 
                        fecha_inicio = ?, 
                        fecha_fin = ?, 
                        id_cliente = ?, 
                        id_usuario = ?, 
                        id_categoria = ?, 
                        id_tarifa = ? 
                    WHERE id_reserva = ?");
    
        $stmt->bind_param("sssissi", $fecha_inicio, $fecha_fin, $dni, $idUsuario, $grupoVehiculo, $codigoTarifa, $id_reserva);

        if ($stmt->execute()) {
            $stmt->close();
            return true; 
        } else {
            echo "Error en la consulta: " . $stmt->error; 
            $stmt->close();
            $this->db->close();
            return false; 
        }
    }

    /**
     * Devolución de vehículo - actualiza reserva como finalizada
     */
    public function devolucionReserva($idReserva, $kilometrosEntrada){
        
       $stmt = $this->db->prepare("UPDATE reservas SET 
                        kilometros_entrada = ?, 
                        estado = 'Finalizada'
                    WHERE id_reserva = ?");
    
        $stmt->bind_param("si", $kilometrosEntrada, $idReserva);
    
        if ($stmt->execute()) {
            $stmt->close();
            return true;  
        } else {
            $stmt->close();
            return false; 
        }
    }

    /**
     * Alquila un vehículo - actualiza reserva como 'En alquiler'
     */
    public function alquilarReserva($id_reserva, $idVehiculo, $kilometrosSalida) {
        $stmt = $this->db->prepare("UPDATE reservas SET
                        id_vehiculo = ?,
                        kilometros_salida = ?,
                        estado = 'En alquiler'
                        WHERE id_reserva = ?");
    
        
        $stmt->bind_param("isi", $idVehiculo, $kilometrosSalida, $id_reserva);
    
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
     * Cancela una reserva - actualiza su estado a 'Cancelada'
     */
    public function eliminarReserva($idReserva){
        $stmt = $this->db->prepare("UPDATE reservas SET 
                        estado = 'Cancelada'
                        WHERE id_reserva = ?");
    
        
        $stmt->bind_param("i", $idReserva);
    
        if ($stmt->execute()) {
            $stmt->close();
            return true; 
        } else {
            echo "Error en la consulta: " . $stmt->error; 
            $stmt->close();
            return false;  
        }
    }
    
}