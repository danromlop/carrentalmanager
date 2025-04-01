<?php


class Cliente{

    //conexión a la bbdd
    private $db;
  
    public function __construct(){

        $database = new Database();
        
        $this->db = $database->getDatabase();
      
    }


    /**
     * Obtiene un array con todos los clientes 
     * */
    public function getClientes(){

        $sql = "SELECT * FROM cliente";

        $resultado = $this->db->query($sql);

        if ($resultado->num_rows > 0) {
            return $resultado->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    /**
     * Recupera un cliente por su número de DNI
     */
    public function getClienteByDNI($dni){
        $sql = "SELECT 
                    id,
                    dni,
                    nombre,
                    apellidos,
                    fecha_nacimiento,
                    numero_carnet_conducir,
                    fecha_expedicion,
                    fecha_caducidad,
                    direccion
                FROM 
                    clientes
                WHERE 
                    dni = ?";

                $stmt = $this->db->prepare($sql);
                if($stmt){
                    $stmt->bind_param("s",$dni);
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
     * Crea un nuevo cliente
     */
    public function nuevoCliente($dni, $nombre, $apellidos, $fecha_nacimiento, $numero_carnet_conducir, $fecha_expedicion, $fecha_caducidad, $direccion){

        $stmt = $this->db->prepare("INSERT INTO clientes (dni, nombre, apellidos, fecha_nacimiento, numero_carnet_conducir, fecha_expedicion, fecha_caducidad, direccion) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("ssssssss", $dni, $nombre, $apellidos, $fecha_nacimiento, $numero_carnet_conducir, $fecha_expedicion, $fecha_caducidad, $direccion);
        
        if ($stmt->execute()) {
            $inserted_id = $this->db->insert_id;
            $stmt->close();
            $this->db->close();
            return  $inserted_id;  
        } else {
            $stmt->close();
            $this->db->close();
            return false;  
        }
        

    }

    /**
     * Actualiza un cliente por su número de ID
     */
    public function actualizarCliente($id, $dni, $nombre, $apellidos, $fecha_nacimiento, $numero_carnet_conducir, $fecha_expedicion, $fecha_caducidad, $direccion) {
      
        $stmt = $this->db->prepare("
            UPDATE clientes 
            SET dni = ?, nombre = ?, apellidos = ?, fecha_nacimiento = ?, numero_carnet_conducir = ?, fecha_expedicion = ?, fecha_caducidad = ?, direccion = ?
            WHERE id = ?
        ");
           
        $stmt->bind_param("ssssssssi", $dni, $nombre, $apellidos, $fecha_nacimiento, $numero_carnet_conducir, $fecha_expedicion, $fecha_caducidad, $direccion, $id);
    
        if ($stmt->execute()) {
            $stmt->close();
            return true;  
        } else {
            $stmt->close();
            return false;  
        }
    }

}