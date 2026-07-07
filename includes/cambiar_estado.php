<?php

session_start();
include 'db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    exit("Acceso denegado");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("Método no permitido");
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$estado = $_POST['estado'] ?? '';

if ($id <= 0) {
    exit("Préstamo inválido");
}

if (!in_array($estado, ['aprobado', 'rechazado'])) {
    exit("Estado inválido");
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn->begin_transaction();

try {

    // Obtener los datos del préstamo y la tasa de interés
    $stmt = $conn->prepare("
        SELECT
            sp.monto_solicitado,
            sp.plazo_meses,
            sp.estado,
            pf.tasa_interes
        FROM solicitudes_prestamos sp
        JOIN productos_financieros pf
            ON sp.producto_id = pf.id
        WHERE sp.id = ?
        FOR UPDATE
    ");

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {
        throw new Exception("Solicitud no encontrada");
    }

    $prestamo = $resultado->fetch_assoc();
    $stmt->close();

    if ($prestamo['estado'] !== 'pendiente') {
        throw new Exception("La solicitud ya fue procesada");
    }

    // Si el préstamo es rechazado, solo cambiar el estado
    if ($estado === 'rechazado') {

        $stmt = $conn->prepare("
            UPDATE solicitudes_prestamos
            SET estado = 'rechazado'
            WHERE id = ?
        ");

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

    } else {

        $monto = floatval($prestamo['monto_solicitado']);
        $plazo = intval($prestamo['plazo_meses']);
        $tasaAnual = floatval($prestamo['tasa_interes']);

        if ($monto <= 0 || $plazo <= 0) {
            throw new Exception("Los datos del préstamo no son válidos");
        }

        // Convertir la tasa anual a tasa mensual
        $tasaMensual = ($tasaAnual / 100) / 12;

        // Calcular cuota fija mensual
        if ($tasaMensual > 0) {
            $montoCuota = (
                $monto * $tasaMensual
            ) / (
                1 - pow(1 + $tasaMensual, -$plazo)
            );
        } else {
            $montoCuota = $monto / $plazo;
        }

        $montoCuota = round($montoCuota, 2);
        $montoTotal = round($montoCuota * $plazo, 2);

        // Aprobar y guardar los valores calculados
        $stmt = $conn->prepare("
            UPDATE solicitudes_prestamos
            SET
                estado = 'aprobado',
                monto_total = ?,
                cuota_mensual = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "ddi",
            $montoTotal,
            $montoCuota,
            $id
        );

        $stmt->execute();
        $stmt->close();

        // Crear las cuotas
        for ($i = 1; $i <= $plazo; $i++) {

            $fechaVencimiento = date(
                'Y-m-d',
                strtotime("+$i month")
            );

            $stmt = $conn->prepare("
                INSERT INTO cuotas_prestamo
                (
                    prestamo_id,
                    numero_cuota,
                    monto,
                    fecha_vencimiento,
                    estado
                )
                VALUES (?, ?, ?, ?, 'pendiente')
            ");

            $stmt->bind_param(
                "iids",
                $id,
                $i,
                $montoCuota,
                $fechaVencimiento
            );

            $stmt->execute();
            $stmt->close();
        }
    }

    $conn->commit();

    header("Location: ../views/admin_solicitudes.php");
    exit();

} catch (Exception $e) {

    $conn->rollback();

    exit("Error al procesar el préstamo: " . $e->getMessage());
}