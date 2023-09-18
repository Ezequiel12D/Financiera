<?php

require_once 'controller/FinancieraController.php';

$controller = new FinancieraController();

$action = $_GET['action'] ?? 'inicio'; 

switch ($action) {
    case 'productos':
        $controller->productos();
        break;
    case 'contacto':
        $controller->contacto();
        break;
    default:
        $controller->inicio();
        break;
}
?>  