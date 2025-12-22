<?php
session_start();
include 'db.php';

/* Seguridad: solo admin */
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../views/home.php");
    exit();
}

/* Validar método y dato */
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['cuota_id'])) {
    header("Location: ../views/admin_cuotas.php");
    exit();
}

$cuota_id = (int) $_POST['cuota_id'];

/* Actualizar estado */
$stmt = $conn->prepare(
    "UPDATE cuotas_prestamo SET estado = 'pagada' WHERE id = ?"
);

$stmt->bind_param("i", $cuota_id);
$stmt->execute();

/* Opcional: verificar si se actualizó algo */
if ($stmt->affected_rows === 0) {
    // Podrías loguear error o mostrar mensaje
}

$stmt->close();

/* Volver a la página anterior */
header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '../views/admin_cuotas.php'));
exit();
