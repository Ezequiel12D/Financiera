<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: home.php");
    exit();
}

$id = $_POST['id'];
$accion = $_POST['accion'];

$conn->begin_transaction();

try {
    $stmt = $conn->prepare("UPDATE solicitudes_prestamos SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $accion, $id);
    $stmt->execute();

    if ($accion === 'aprobado') {
        $stmt = $conn->prepare("
            SELECT monto_total, plazo_meses 
            FROM solicitudes_prestamos 
            WHERE id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($monto_total, $plazo);
        $stmt->fetch();
        $stmt->close();

        $cuota = round($monto_total / $plazo, 2);

        for ($i = 1; $i <= $plazo; $i++) {
            $fecha = date('Y-m-d', strtotime("+$i month"));

            $stmt = $conn->prepare("
                INSERT INTO cuotas_prestamo 
                (prestamo_id, numero_cuota, monto, fecha_vencimiento)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->bind_param("iids", $id, $i, $cuota, $fecha);
            $stmt->execute();
        }
    }

    $conn->commit();
    header("Location: ../views/admin_solicitudes.php");

} catch (Exception $e) {
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}
