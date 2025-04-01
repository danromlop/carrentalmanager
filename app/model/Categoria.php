<?php


class Categoria{


    //conexión con bbdd
    private $db;

    public function __construct(){
       
        
        $database = new Database();
        
        $this->db = $database->getDatabase();
      
    }

    /**
     * Obtiene un array con todsa las categorías en activo
     */
    public function getCategorias(){
        $sql = "SELECT *
                FROM 
                    categorias
                WHERE
                    eliminado = FALSE;";
        
        $resultado = $this->db->query($sql);

        if ($resultado->num_rows > 0) {
            // Devolver los resultados como un array asociativo
            return $resultado->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    /**
     * Recupera una categoría por su ID literal
     */
    public function getCategoriaPorIdCat($idCategoria){
        $sql = "SELECT
                    *
                FROM
                    categorias
                WHERE
                    id_categoria = ? AND eliminado = FALSE;";
        $stmt = $this->db->prepare($sql);
        if($stmt){
            $stmt->bind_param("s",$idCategoria);
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
     * Recupera una categoría por ID
     */
    public function getCategoriaPorId($id){
        $sql = "SELECT
                    *
                FROM
                    categorias
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
     * Crea una categoría
     */
    public function crearCategoria($idCategoria, $nombreCategoria, $eliminado){
        $stmt = $this->db->prepare("INSERT INTO categorias (id_categoria, nombre_categoria, eliminado)
        VALUES (?,?,?)");

        $stmt->bind_param("ssi", $idCategoria, $nombreCategoria, $eliminado);
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
     * Edita una categoría existente por ID
     */
    public function editarCategoria($idCategoria, $nombreCategoria, $id){
        $stmt = $this->db->prepare("UPDATE categorias SET
            id_categoria = ?,
            nombre_categoria = ?
            WHERE id = ?");

            $stmt->bind_param("ssi", $idCategoria, $nombreCategoria, $id);

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
     * Elimina una categoría actualizado su campo eliminado a True
     */
    public function eliminarCategoria($idCategoria){
        $stmt = $this->db->prepare("UPDATE categorias SET
                        eliminado = TRUE
                        WHERE id_categoria = ?");
        $stmt->bind_param("s", $idCategoria);

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