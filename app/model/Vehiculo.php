<?php


class Vehiculo{

    // conexión a la bbdd
    private $db;

    public function __construct(){
       
        
        $database = new Database();
        
        $this->db = $database->getDatabase();
      
    }

    /**
     * Recupera un array con todos los vehículos en activo. Devuelve un array vacío si no hay resultados.
     */
    public function getVehiculo(){
        $sql = "SELECT 
        vehiculos.id,
        vehiculos.matricula,
        vehiculos.marca,
        vehiculos.modelo,
        vehiculos.id_categoria,
        categorias.nombre_categoria,  
        categorias.id AS categoria_id,
        categorias.id_categoria as categoria_coche,
        vehiculos.kilometros_actuales,
        vehiculos.alquilado
        FROM 
            vehiculos
        JOIN 
            categorias ON vehiculos.id_categoria = categorias.id
        WHERE
            vehiculos.eliminado = FALSE;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            return $result->fetch_all(MYSQLI_ASSOC);
        } else{
            return [];
        }

    }

    /**
     * Recupera un vehículo por matrícula
     */
    public function getVehiculoPorMatricula($matricula){
        //TODO select vehiculo con matricula
        $sql = "SELECT 
            vehiculos.id,
            vehiculos.matricula,
            vehiculos.marca,
            vehiculos.modelo,
            vehiculos.id_categoria,
            categorias.nombre_categoria,  
            categorias.id AS categoria_id,
            categorias.id_categoria as categoria_coche,
            vehiculos.kilometros_actuales,
            vehiculos.alquilado
        FROM 
            vehiculos
        JOIN 
            categorias ON vehiculos.id_categoria = categorias.id
        WHERE
            vehiculos.matricula = '$matricula' AND vehiculos.eliminado = FALSE";

        $resultado = $this->db->query($sql);

        if ($resultado->num_rows > 0) {
            // Devolver los resultados como un array asociativo
            return $resultado->fetch_assoc();
        } else {
            return false;
        }

    }

    /**
     * Recupera un vehículo por ID
     */
    public function getVehiculoPorId($id){
        //TODO select vehiculo con matricula
        $sql = "SELECT 
            vehiculos.id,
            vehiculos.matricula,
            vehiculos.marca,
            vehiculos.modelo,
            vehiculos.id_categoria,
            categorias.nombre_categoria,  
            categorias.id AS categoria_id,
            categorias.id_categoria as categoria_coche,
            vehiculos.kilometros_actuales,
            vehiculos.alquilado
        FROM 
            vehiculos
        JOIN 
            categorias ON vehiculos.id_categoria = categorias.id
        WHERE
            vehiculos.id = '$id' AND vehiculos.eliminado = FALSE";

        $resultado = $this->db->query($sql);

        if ($resultado->num_rows > 0) {
            // Devolver los resultados como un array asociativo
            return $resultado->fetch_assoc();
        } else {
            return false;
        }

    }


    /***
     * Actualiza el campo alquilado de un vehículo a True
     */
    public function alquilarVehiculo($matricula){
        $stmt = $this->db->prepare("UPDATE vehiculos SET
            alquilado = TRUE
            WHERE matricula = ?");

        $stmt->bind_param("s", $matricula);
        if ($stmt->execute()) {
            $stmt->close();
            return true; 
        } else {
            $stmt->close();
            return false;  
        }


    }

    /**
     * Actualiza el campo alquilado de un vehículo a False
     */
    public function devolverVehiculo($matricula){
        $stmt = $this->db->prepare("UPDATE vehiculos SET
            alquilado = FALSE
            WHERE matricula = ?");

        $stmt->bind_param("s", $matricula);
        if ($stmt->execute()) {
            $stmt->close();
            return true; 
        } else {
            $stmt->close();
            return false;  
        }


    }

    /**
     * Actualiza los kilómetros del vehículo
     */
    public function updateKilometrosVehiculo($matricula, $km){
        $stmt = $this->db->prepare("UPDATE vehiculos SET
        kilometros_actuales = ?
        WHERE matricula = ?");

        $stmt->bind_param("is", $km, $matricula);
        if ($stmt->execute()) {
            $stmt->close();
            return true; 
        } else {
            $stmt->close();
            return false;  
        }
    }

    /**
     * Elimina un vehículo actualizando su campo eliminado a True
     */
    public function eliminarVehiculo($matricula){
        $stmt = $this->db->prepare("UPDATE vehiculos SET 
                        eliminado = TRUE
                        WHERE matricula = ?");
    
        
        $stmt->bind_param("s", $matricula);
    
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
     * Edita un vehículo existente por ID
     */
    public function editarVehiculo($matricula, $marca, $modelo, $idCategoria, $kilometrosActuales, $id){
        $stmt = $this->db->prepare("UPDATE vehiculos SET 
                        matricula = ?,
                        marca = ?,
                        modelo = ?,
                        id_categoria = ?,
                        kilometros_actuales = ?                        
                        WHERE id = ?");
    
        
        $stmt->bind_param("ssssii", $matricula, $marca, $modelo, $idCategoria, $kilometrosActuales, $id);
    
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
     * Crea un vehículo
     */
    public function crearVehiculo($matricula, $marca, $modelo, $idCategoria, $kilometrosActuales, $alquilado, $eliminado){
        $stmt = $this->db->prepare("INSERT INTO vehiculos (matricula, marca, modelo, id_categoria, kilometros_actuales, alquilado, eliminado) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("ssssiii", $matricula, $marca, $modelo, $idCategoria, $kilometrosActuales, $alquilado, $eliminado);
            
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