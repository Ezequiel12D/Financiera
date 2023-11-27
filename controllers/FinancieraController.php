<?php

$accion = isset($_GET['accion']) ? $_GET['accion'] : 'inicio';

if ($accion === 'inicio') {
    $saldo = 10000;
    $ultima_transaccion = 'Compra de acciones';
    include 'vista_inicio.php';
} else {

}
?>