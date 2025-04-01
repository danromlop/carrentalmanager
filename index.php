<?php


require_once(__DIR__ . '/app/controller/UsuarioController.php');
require_once(__DIR__ . '/app/controller/PageController.php');
require_once(__DIR__ . '/app/controller/ReservaController.php');
require_once(__DIR__ . '/app/controller/VehiculoController.php');
require_once(__DIR__ . '/app/controller/CategoriasController.php');
require_once(__DIR__ . '/app/controller/TarifaController.php');
require_once 'config.php'; 
require_once __DIR__ . '/app/model/database.php'; 
require_once __DIR__ . '/app/model/Usuario.php'; 



if(!isset($_SESSION)){
    session_start();
}
/* ENRUTADOR */
//obtiene la URI de la petición
$request = $_SERVER['REQUEST_URI'] ;

//si no hay una petición definida, redirige al home
if ($request == '/') {
   
    $request = '/home';
 
}

//array que define las posibles rutas de la aplicación, con sus correspondientes controladores y métodos
$routes = [
    //rutas usuarios
    '/usuario/lista' => ['controller' => 'UsuarioController', 'method' => 'listaUsuarios'],
    '/usuario/nuevo' => ['controller' => 'UsuarioController', 'method' => 'nuevo'],
    '/usuario/editar' => ['controller' => 'UsuarioController', 'method' => 'editar'],
    '/usuario/crearUsuario' => ['controller' => 'UsuarioController', 'method' => 'crearUsuario'],
    '/usuario/editarUsuario' => ['controller' => 'UsuarioController', 'method' => 'editarUsuario'],
    '/usuario/eliminar' => ['controller' => 'UsuarioController', 'method' => 'eliminar'],

    //rutas ReservaController
    '/reserva/nueva' => ['controller' => 'ReservaController', 'method' => 'nueva'],
    '/reserva/nueva/crear' => ['controller' => 'ReservaController', 'method' => 'crearReserva'],
    '/reserva/buscar' => ['controller' => 'ReservaController', 'method' => 'buscar'],
    '/reserva/editar/{id}' => ['controller' => 'ReservaController', 'method' => 'editar'],
    '/reserva/alquilar/{id}' => ['controller' => 'ReservaController', 'method' => 'alquilar'],
    '/reserva/alquilarVehiculo' => ['controller' => 'ReservaController', 'method' => 'alquilarVehiculo'],
    '/reserva/devolucion' => ['controller' => 'ReservaController', 'method' => 'devolucion'],
    '/reserva/buscarDevolucion' => ['controller' => 'ReservaController', 'method' => 'buscarDevolucion'],
    '/reserva/devolverVehiculo/{id}' => ['controller' => 'ReservaController', 'method' => 'devolverVehiculo'],
    '/reserva/buscarDevolucion' => ['controller' => 'ReservaController', 'method' => 'buscarDevolucion'],
    '/reserva/realizarDevolucion/{id}' => ['controller' => 'ReservaController', 'method' => 'realizarDevolucion'],
    '/reserva/eliminar' => ['controller' => 'ReservaController', 'method' => 'eliminar'],
    '/index' => ['controller' => 'ReservaController', 'method' => 'lista'],

    //rutas VehiculoController
    '/flota' => ['controller' => 'VehiculoController', 'method' => 'flota'],
    '/flota/nuevo' => ['controller' => 'VehiculoController', 'method' => 'nuevo'],
    '/flota/crear' => ['controller' => 'VehiculoController', 'method' => 'crearVehiculo'],
    '/flota/editar' => ['controller' => 'VehiculoController', 'method' => 'editar',],
    '/flota/editarVehiculo' => ['controller' => 'VehiculoController', 'method' => 'editarVehiculo',],
    '/flota/eliminar' => ['controller' => 'VehiculoController', 'method' => 'eliminar',],

    //rutas Tarifas
    '/tarifas/lista' => ['controller' => 'TarifaController', 'method' => 'tarifas'],
    '/tarifas/nueva' => ['controller' => 'TarifaController', 'method' => 'nuevo'],
    '/tarifas/crearTarifa' => ['controller' => 'TarifaController', 'method' => 'crearTarifa'],
    '/tarifas/eliminar' => ['controller' => 'TarifaController', 'method' => 'eliminar'],
    '/tarifas/editarTarifa' => ['controller' => 'TarifaController', 'method' => 'editarTarifa'],
    '/tarifas/editar' => ['controller' => 'TarifaController', 'method' => 'editar'],

    //rutas Categoría
    '/categorias/lista' => ['controller' => 'CategoriasController', 'method' => 'lista'],
    '/categorias/nueva' => ['controller' => 'CategoriasController', 'method' => 'nueva'],
    '/categorias/crearCategoria' => ['controller' => 'CategoriasController', 'method' => 'crearCategoria'],
    '/categorias/editar' => ['controller' => 'CategoriasController', 'method' => 'editar'],
    '/categorias/editarCategoria' => ['controller' => 'CategoriasController', 'method' => 'editarCategoria'],
    '/categorias/eliminar' => ['controller' => 'CategoriasController', 'method' => 'eliminarCategoria'],

    //rutas PageController
    
    '/home' => ['controller' => 'PageController', 'method' => 'home'],
    '/login' => ['controller' => 'PageController', 'method' => 'login'],
    '/logout' => ['controller' => 'PageController', 'method' => 'logout']
   
];



/**
 * Función que maneja rutas dinámicas, obteniendo el id del recurso enviado por la petición
 */
function matchRoute($request, $routes) {
    foreach ($routes as $route => $target) {
        //convierte la ruta a una expresión regular: capturar el id
        $routeRegex = preg_replace('/\{id\}/', '(\d+)', $route);
        $routeRegex = str_replace('/', '\/', $routeRegex);
        $routeRegex = '/^' . $routeRegex . '$/'; 

        //en caso de que la ruta coincida con la expresión regular, captura el id y devuelve la ruta con el parámetro
        if (preg_match($routeRegex, $request, $matches)) {
            
            if (isset($matches[1])) {
                $target['params'] = ['id' => $matches[1]]; 
            }
            return $target; 
        }
    }
    return false; //devuelve falso si no hay id
}

//verifica que la ruta exista
$route = matchRoute($request, $routes);

if ($route) {
    $controllerName = $route['controller'];
    $methodName = $route['method'];
} else {
       
    header("HTTP/1.0 404 Not Found");
    echo "404 Not Found - Ruta no encontrada";
    exit;
}

//verifica que el controlador exista y crea una instancia
if (class_exists($controllerName)) {
    $controller = new $controllerName();
    

    // verifica si el método existe en el controlador
    if (method_exists($controller, $methodName)) {
        
        if (isset($route['params'])) {
            // llama al método con parámetros
            $controller->$methodName($route['params']);
        } else {
            
    
            // llama al método sin parámetros
            $controller->$methodName();
        }
    } else {
        // si el método no es encontrado
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found - Método del controlador no encontrado";
    }
} else {
    // si el controlador no es encontrado
    header("HTTP/1.0 404 Not Found");
    echo "404 Not Found - Controlador no encontrado";
}


