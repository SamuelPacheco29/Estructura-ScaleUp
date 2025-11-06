<?php
 
// Router Principal
 
session_start();

// Obtener la accion
$accion = $_GET['action'] ?? 'login';

// Router
switch ($accion) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            require_once 'controllers/AutentificadorCtrl.php';
            $controlador = new AutentificadorCtrl();
            $controlador->login();            
        } else {
            require_once 'views/login.php';
        }
        break;

    case 'registrar':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            require_once 'controllers/AutentificadorCtrl.php';
            $controlador = new AutentificadorCtrl();
            $controlador->registrar();            
        } else {
            require_once 'views/registro.php';
        }
        break;

    case 'verificar':
        require_once 'controllers/AutentificadorCtrl.php';
        $controlador = new AutentificadorCtrl();
        $controlador->verificar();
        break;

    case 'reenviarCodigo':
        require_once 'controllers/AutentificadorCtrl.php';
        $controlador = new AutentificadorCtrl();
        $controlador->reenviarCodigo();
        break;    

    case 'logout':
        require_once 'controllers/AutentificadorCtrl.php';
        $controlador = new AutentificadorCtrl();
        $controlador->logout();
        break;

    case 'dashboard':
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        require_once 'controllers/ProductoCtrl.php';
        $controlador = new ProductoCtrl;
        $controlador->dashboard();
        break;

    case 'productos':
        // Verificar si esta logueado
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        
        require_once 'controllers/ProductoCtrl.php';
        $controlador = new ProductoCtrl();
        
        $method = $_GET['method'] ?? 'index';
        
        switch ($method) {
            case 'crear':
                $controlador->crearRegistro();
                break;
            case 'actualizar':
                $controlador->actualizarRegistro();
                break;
            case 'eliminar':
                $controlador->eliminarRegistro();
                break;
            case 'exportar_csv':
                $controlador->exportarCSV();
                break;
            case 'dashboard':
                $controlador->dashboard();
                break;
            default:
                $controlador->index();
                break;
        }
        break;

        case 'categorias':
            // Verificar si esta logueado
            if (!isset($_SESSION['usuario_id'])) {
                header('Location: index.php?action=login');
                exit;
            }
            
            require_once 'controllers/CategoriaCtrl.php';
            $controlador = new CategoriaCtrl();
            
            $method = $_GET['method'] ?? 'index';
            
            switch ($method) {
                case 'crear':
                    $controlador->crearRegistro();
                    break;
                case 'actualizar':
                    $controlador->actualizarRegistro();
                    break;
                case 'eliminar':
                    $controlador->eliminarRegistro();
                    break;
                case 'exportar_csv':
                    $controlador->exportarCSV();
                default:
                    $controlador->index();
                    break;
            }
            break;

        case 'movimientos':
            //Verificar si esta logueado
            if (!isset($_SESSION['usuario_id'])) {
                header('Location: index.php?action=login');
                exit;
            }

        require_once 'controllers/MovimientosCtrl.php';
        $controlador = new MovimientosCtrl();

        $method = $_GET['method'] ?? 'index';

        switch ($method) {
            case 'crear':
                $controlador->crearRegistro();
                break;
            case 'exportar_csv':
                $controlador->exportarCSV();
                break;
            default :
                $controlador->index();
                break;
        }
        break;

    case 'usuarios':
        // Verificar si está logueado y sea admin
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        
        if ($_SESSION['usuario_rol'] !== 'admin') {
            $_SESSION['message'] = 'Acceso no autorizado';
            $_SESSION['message_type'] = 'error';
            header('Location: index.php?action=dashboard');
            exit;
        }

        require_once 'controllers/UsuarioCtrl.php';
        $controlador = new UsuarioCtrl();

        $method = $_GET['method'] ?? 'index';

        switch ($method) {
            case 'cambiarRol':
                $controlador->cambiarRol();
                break;
            case 'exportar_csv':
                $controlador->exportarCSV();
                break;
            default:
                $controlador->index();
                break;
        }
        break;

    case 'ayuda':
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        
        include 'views/ayuda.php';
        break;
}
?>