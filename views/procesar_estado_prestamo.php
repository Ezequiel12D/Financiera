<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: home.php");
    exit();
}

// Validar POST
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$accion = isset($_POST['accion']) ? $_POST['accion'] : '';

if ($id <= 0 || !in_array($accion, ['aprobado', 'rechazado'])) {
    die("Datos inválidos.");
}

$conn->begin_transaction();

try {
    // Actualizar estado del préstamo
    $stmt = $conn->prepare("UPDATE solicitudes_prestamos SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $accion, $id);
    $stmt->execute();
    $stmt->close();

    if ($accion === 'aprobado') {
        // Obtener monto total y plazo
        $stmt = $conn->prepare("SELECT monto_total, plazo_meses FROM solicitudes_prestamos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($monto_total, $plazo);
        $stmt->fetch();
        $stmt->close();

        $cuota = round($monto_total / $plazo, 2);
        $fecha_base = new DateTime(); // hoy

        for ($i = 1; $i <= $plazo; $i++) {
            $fecha = clone $fecha_base;
            $fecha->modify("+$i month");
            $fecha_str = $fecha->format('Y-m-d');

            $stmt = $conn->prepare("INSERT INTO cuotas_prestamo (prestamo_id, numero_cuota, monto, fecha_vencimiento) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iids", $id, $i, $cuota, $fecha_str);
            $stmt->execute();
            $stmt->close();
        }
    }

    $conn->commit();
    header("Location: ../views/admin_solicitudes.php");
    exit();

} catch (Exception $e) {
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}
