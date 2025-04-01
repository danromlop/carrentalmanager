<?php

include_once __DIR__ . '/../model/Categoria.php';



class CategoriasController {
    //instancia del modelo
    private $categoriasModel;

    public function __construct() {
        $this->categoriasModel = new Categoria();
    }

    /**
     * Obtiene las categorias y redirige al usuario al listado de categorias
     */
    public function lista(){
        $categorias = $this->categoriasModel->getCategorias();

        include __DIR__ . "/../view/categorias.php";
    }

    /**
     * Redirige al usuario al formulario de creación de categorias
     */
    public function nueva(){

        include __DIR__ . "/../view/crearcategoria.php";
    }

    /**
     * Llama al modelo para crear una categoría con los datos del formulario
     */
    public function crearCategoria(){

        $errores = [];

        $idCategoria = $_POST["id_categoria"];
        $nombreCategoria = $_POST["nombre_categoria"];
        $eliminado = 0;

        if(empty($idCategoria) || empty($nombreCategoria)){
            array_push($errores, "Completa todos los campos");
        }

        $categoriaValidacion =$this->categoriasModel->getCategoriaPorId($idCategoria);

        if($categoriaValidacion && !$categoriaValidacion["eliminado"]){
            array_push($errores, "Ese ID ya existe");
        }

        if(strlen($idCategoria) > 1 ){
            array_push($errores, "El ID no puede tener más de 1 caracter");
        }

        if (!empty($errores)) {
                
            $_SESSION["errores_crearC"] = $errores;
            header("Location: /categorias/nueva"); 
            exit; 
        
        }else{
            $nuevaCategoria = $this->categoriasModel->crearCategoria($idCategoria, $nombreCategoria, $eliminado);
            if(!$nuevaCategoria){
                echo "Error al crear Categoria";
                exit;
            }else{
                $_SESSION["success_crearC"] = "Categoría con ID " . $idCategoria . " creada con éxito.";
                header("Location: /categorias/lista");
            }

        }
        
    }

    /**
     * Recupera los datos de la categoría seleccionada y redirige al usuario al        formulario de edición
     */
    public function editar(){

        if(isset($_POST["id_categoria"])){
            $id = $_POST["id_categoria"];
        }else{
            $id = $_SESSION["form_data_t"]["id"];
        }

        $categoria = $this->categoriasModel->getCategoriaPorId($id);
        if(!$categoria){
            echo "Error al get categoria";
            exit;
        }
        
        include __DIR__ . "/../view/actualizarcategoria.php";
    }
    
    /**
     * Llama al modelo para editar la categoría con los datos del formulario
     */
    public function editarCategoria(){

        $errores = [];

        
        $_SESSION["form_data_t"] = $_POST;

        $id = $_POST["id"];
        $idCategoria = $_POST["id_categoria"];
        $nombreCategoria = $_POST["nombre_categoria"];
        $idCategoriaEditar = $_POST["idCategoriaEditar"];

        if(empty($nombreCategoria)){
            array_push($errores, "Completa todos los campos");
        }

        $idEditar = $this->categoriasModel->getCategoriaPorIdCat($idCategoria);

        if($idEditar && $idEditar["id"] != $id ){
            array_push($errores, "Ese ID ya existe " . $idEditar["id_categoria"] . $idCategoriaEditar);
        }

        if(strlen($idCategoria) > 1 ){
            array_push($errores, "El ID no puede tener más de 1 caracter");
        }
        
        if (!empty($errores)) {
                
            $_SESSION["errores_crearC"] = $errores;
            header("Location: /categorias/editar"); 
            exit; 
        
        }else{
            $editarCategoria = $this->categoriasModel->editarCategoria($idCategoria, $nombreCategoria, $id);
            if(!$editarCategoria){
                echo "Error al editar Categoria";
                exit;
            }else{
                $_SESSION["success_crearC"] = "Categoría con ID " . $id . " editada con éxito. ";
                header("Location: /categorias/lista");
            }

        }

    }
    /**
     * Realiza el borrado lógico de la categoría seleccionada
     */
    public function eliminarCategoria(){
        $idCategoria = $_POST["id_categoria"];

        $categoriaEliminada = $this->categoriasModel->eliminarCategoria($idCategoria);
        if(!$categoriaEliminada){
            echo "Error al eliminar tarifa";
            exit;
        }else{
            $_SESSION['success_crearC'] = "Categoría con ID " . $idCategoria . " eliminada correctamente.";
            header("Location: /categorias/lista");
        }
    }




}





?>
