<?php
require_once 'model/FinancieraModel.php';

$action = $_GET['action'] ?? 'inicio';

include 'view/header.php';

if ($action === 'productos') {
    include 'view/productos.php';
} elseif ($action === 'contacto') {
    include 'view/contacto.php';
} else {
    include 'view/home.php';
}

include 'view/footer.php';
?>