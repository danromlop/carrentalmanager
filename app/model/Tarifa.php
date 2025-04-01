<?php


class Tarifa{

    //conexión con bbdd
    private $db;

    public function __construct(){
       
        
        $database = new Database();
        
        $this->db = $database->getDatabase();
      
    }

    /**
     * Obtiene todas las tarifas que no hayan sido marcadas como eliminadas (borrado lógico)
     */
    public function getTarifas(){
        $sql = "SELECT 
                    *
                FROM 
                    tarifas
                WHERE
                    eliminado = FALSE;";


            $resultado = $this->db->query($sql);

            if ($resultado->num_rows > 0) {
                
                return $resultado->fetch_all(MYSQLI_ASSOC); 
            } else {
                return [];
            }
        }
    
    /**
     * Obtiene una tarifa por ID   
     */
    public function getTarifaPorId($id){
            $sql = "SELECT 
                        *
                    FROM 
                        tarifas
                    WHERE 
                        id = ? AND eliminado = FALSE;";

            $stmt = $this->db->prepare($sql);
            if($stmt){
                $stmt->bind_param("i",$id);
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
     * Obtiene una tarifa por código 
     */
    public function getTarifaPorCodigo($codigoTarifa){
            $sql = "SELECT 
                        *
                    FROM 
                        tarifas
                    WHERE 
                        id = ? AND eliminado = FALSE;";


                
            $stmt = $this->db->prepare($sql);
            if($stmt){
                $stmt->bind_param("s",$codigoTarifa);
                $stmt->execute();
                $res = $stmt->get_result();
                if($res->num_rows > 0){
                    return $res->fetch_assoc();
                }else{
                    return false; //esta devolviendo false
                }
            }else{
                return false;
            }
        }

    /**
     * Crea una nueva tarifa
     */
    public function crearTarifa($codigoTarifa, $nombreTarifa, $precioDiario, $eliminado){
        $stmt = $this->db->prepare("INSERT INTO tarifas (codigo_tarifa, nombre_tarifa, precio_diario, eliminado)
        VALUES (?,?,?,?)");

        $stmt->bind_param("ssdi", $codigoTarifa, $nombreTarifa, $precioDiario, $eliminado);

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
     * Edita una tarifa existente por ID
     */
    public function editarTarifa($codigoTarifa, $nombreTarifa, $precioDiario, $id){
        $stmt = $this->db->prepare("UPDATE tarifas SET
            codigo_tarifa = ?,
            nombre_tarifa = ?,
            precio_diario = ?
            WHERE id = ?");

            $stmt->bind_param("ssdi", $codigoTarifa, $nombreTarifa, $precioDiario, $id);
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
     * Realiza el borrado lógico de la tarifa seleccionada
     */
    public function eliminarTarifa($idTarifa){
        $stmt = $this->db->prepare("UPDATE tarifas SET
                        eliminado = TRUE
                        WHERE id = ?");
        $stmt->bind_param("i", $idTarifa);

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

 