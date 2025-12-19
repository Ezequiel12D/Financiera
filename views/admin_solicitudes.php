<?php
session_start();
include '../includes/db.php';

/* Verificar login */
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

/* Verificar rol admin */
$stmt = $conn->prepare("SELECT rol FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $_SESSION['usuario_id']);
$stmt->execute();
$stmt->bind_result($rol);
$stmt->fetch();
$stmt->close();

if ($rol !== 'admin') {
    echo "Acceso denegado.";
    exit();
}

/* Obtener solicitudes */
$sql = "
SELECT 
    sp.id,
    u.nombre,
    u.apellido,
    pf.nombre AS producto,
    sp.monto_solicitado,
    sp.estado,
    sp.fecha_solicitud
FROM solicitudes_prestamos sp
JOIN usuarios u ON sp.usuario_id = u.id
JOIN productos_financieros pf ON sp.producto_id = pf.id
ORDER BY sp.fecha_solicitud DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Administrar Solicitudes</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
</head>

<body class="container mt-4">

    <h2 class="mb-4">Panel de Solicitudes de Préstamos</h2>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Producto</th>
                <th>Monto</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>

            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nombre'] . " " . $row['apellido'] ?></td>
                    <td><?= $row['producto'] ?></td>
                    <td>$<?= number_format($row['monto_solicitado'], 2, ',', '.') ?></td>
                    <td>
                        <span class="badge 
                    <?= $row['estado'] == 'pendiente' ? 'bg-warning' :
                        ($row['estado'] == 'aprobado' ? 'bg-success' : 'bg-danger') ?>">
                            <?= ucfirst($row['estado']) ?>
                        </span>
                    </td>
                    <td><?= $row['fecha_solicitud'] ?></td>
                    <td class="d-flex gap-1">
                        <a href="detalle_prestamo.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">
                            📄 Detalle
                        </a>

                        <?php if ($row['estado'] === 'aprobado'): ?>
                            <a href="admin_cuotas.php?prestamo_id=<?= $row['id'] ?>" class="btn btn-info btn-sm">
                                📆 Cuotas
                            </a>
                        <?php endif; ?>
                    </td>

                </tr>
            <?php endwhile; ?>

        </tbody>
    </table>

    <a href="home.php" class="btn btn-secondary mt-3">⬅ Volver al Home</a>

</body>

</html>