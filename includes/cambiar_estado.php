<?php
session_start();
include 'db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    exit("Acceso denegado");
}

$id = $_GET['id'];
$estado = $_GET['estado'];

if (!in_array($estado, ['aprobado', 'rechazado'])) {
    exit("Estado inválido");
}

/* Cambiar estado del préstamo */
$stmt = $conn->prepare("UPDATE solicitudes_prestamos SET estado = ? WHERE id = ?");
$stmt->bind_param("si", $estado, $id);
$stmt->execute();
$stmt->close();

/* Si se aprueba → generar cuotas */
if ($estado === 'aprobado') {

    $sql = "
    SELECT sp.monto_solicitado, sp.plazo_meses
    FROM solicitudes_prestamos sp
    WHERE sp.id = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($monto, $plazo);
    $stmt->fetch();
    $stmt->close();

    $monto_cuota = $monto / $plazo;

    for ($i = 1; $i <= $plazo; $i++) {

        $fecha_vencimiento = date('Y-m-d', strtotime("+$i month"));

        $stmt = $conn->prepare("
            INSERT INTO cuotas_prestamos 
            (prestamo_id, numero_cuota, monto, fecha_vencimiento, estado)
            VALUES (?, ?, ?, ?, 'pendiente')
        ");

        $stmt->bind_param(
            "iids",
            $id,
            $i,
            $monto_cuota,
            $fecha_vencimiento
        );

        $stmt->execute();
        $stmt->close();
    }
}

header("Location: ../views/admin_solicitudes.php");
exit();
