<?php

require_once __DIR__ . "\..\..\config.php";

class Database{
    private $db;

    /**
     * Conexión con la base de datos usando las variables en el archivo config.php
     */
    public function __construct() {

        try{
            
            $this->db = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
        }catch(Exception $e){
            die("Error al conectar la bbdd ". $e->getMessage());
        }
    }

    public function getDatabase(){
        return $this->db;
    }


}

?>
