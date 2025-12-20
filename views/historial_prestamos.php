<?php
session_start();
include '../includes/db.php';

/* Verificar login */
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

/* Obtener solicitudes del usuario */
$sql = "
SELECT 
    sp.id,
    pf.nombre AS producto,
    sp.monto_solicitado,
    sp.monto_total,
    sp.cuota_mensual,
    sp.estado,
    sp.plazo_meses,
    sp.fecha_solicitud
FROM solicitudes_prestamos sp
JOIN productos_financieros pf ON sp.producto_id = pf.id
WHERE sp.usuario_id = ?
ORDER BY sp.fecha_solicitud DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mis Préstamos</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
</head>

<body class="container mt-4">

    <h2 class="mb-4">Mis Solicitudes de Préstamo</h2>

    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-info">
            Todavía no realizaste ninguna solicitud.
        </div>
    <?php else: ?>

        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Monto</th>
                    <th>Plazo</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Cuota</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['producto'] ?></td>
                        <td>$<?= number_format($row['monto_solicitado'], 2, ',', '.') ?></td>
                        <td><?= $row['plazo_meses'] ?> meses</td>
                        <td>
                            <span class="badge 
                            <?= $row['estado'] == 'pendiente' ? 'bg-warning' :
                                ($row['estado'] == 'aprobado' ? 'bg-success' : 'bg-danger') ?>">
                                <?= ucfirst($row['estado']) ?>
                            </span>
                        </td>
                        <td><?= $row['fecha_solicitud'] ?></td>
                        <td>
                            <?= $row['monto_total'] ? '$' . number_format($row['monto_total'], 2, ',', '.') : '-' ?>
                        </td>
                        <td>
                            <?= $row['cuota_mensual'] ? '$' . number_format($row['cuota_mensual'], 2, ',', '.') : '-' ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    <?php endif; ?>

    <a href="home.php" class="btn btn-secondary mt-3">⬅ Volver al Home</a>

</body>

</html>