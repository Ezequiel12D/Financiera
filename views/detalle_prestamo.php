<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: home.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: admin_solicitudes.php");
    exit();
}

$prestamo_id = intval($_GET['id']);

$sql = "
SELECT 
    sp.id,
    sp.monto_solicitado,
    sp.estado,
    sp.tipo_empleo,
    sp.ingresos_mensuales,
    sp.motivo_prestamo,
    sp.plazo_meses,
    sp.fecha_solicitud,
    u.nombre,
    u.apellido,
    u.email,
    pf.nombre AS producto
FROM solicitudes_prestamos sp
JOIN usuarios u ON sp.usuario_id = u.id
JOIN productos_financieros pf ON sp.producto_id = pf.id
WHERE sp.id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $prestamo_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Solicitud no encontrada.";
    exit();
}

$prestamo = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalle del Préstamo</title>
    <link rel="stylesheet" href="../css/styles_home.css">
</head>

<body>
    <h2>Detalle de la Solicitud</h2>

    <div style="max-width:700px;margin:auto;background:#fff;padding:20px;border-radius:8px">

        <h3>Cliente</h3>
        <p><strong>Nombre:</strong> <?= $prestamo['nombre'] . ' ' . $prestamo['apellido'] ?></p>
        <p><strong>Email:</strong> <?= $prestamo['email'] ?></p>

        <hr>

        <h3>Préstamo</h3>
        <p><strong>Producto:</strong> <?= $prestamo['producto'] ?></p>
        <p><strong>Monto:</strong> $<?= number_format($prestamo['monto_solicitado'], 2, ',', '.') ?></p>
        <p><strong>Plazo:</strong> <?= $prestamo['plazo_meses'] ?> meses</p>
        <p><strong>Tipo de empleo:</strong> <?= ucfirst($prestamo['tipo_empleo']) ?></p>
        <p><strong>Ingresos:</strong> $<?= number_format($prestamo['ingresos_mensuales'], 2, ',', '.') ?></p>
        <p><strong>Motivo:</strong> <?= $prestamo['motivo_prestamo'] ?></p>
        <p><strong>Estado:</strong> <?= ucfirst($prestamo['estado']) ?></p>
        <p><strong>Fecha:</strong> <?= $prestamo['fecha_solicitud'] ?></p>

        <?php if ($prestamo['estado'] === 'pendiente'): ?>
            <form method="post" action="../includes/cambiar_estado.php" style="margin-top:20px">
                <input type="hidden" name="id" value="<?= $prestamo['id'] ?>">

                <button type="submit" name="estado" value="aprobado"
                    style="background:green;color:white;padding:10px 20px;border:none;border-radius:5px">
                    Aprobar
                </button>

                <button type="submit" name="estado" value="rechazado"
                    style="background:red;color:white;padding:10px 20px;border:none;border-radius:5px">
                    Rechazar
                </button>
            </form>
        <?php else: ?>
            <p><strong> Esta solicitud ya fue procesada.</strong></p>
        <?php endif; ?>

        <br>
        <a href="admin_solicitudes.php">⬅ Volver al panel</a>

    </div>

</body>

</html>