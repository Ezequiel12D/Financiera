<?php
session_start();
include 'db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../views/home.php");
    exit();
}

$cuota_id = intval($_POST['cuota_id']);

$stmt = $conn->prepare("UPDATE cuotas_prestamo SET estado = 'pagada' WHERE id = ?");
$stmt->bind_param("i", $cuota_id);
$stmt->execute();

header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
