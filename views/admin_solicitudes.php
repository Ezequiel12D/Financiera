<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("SELECT rol FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $_SESSION['usuario_id']);
$stmt->execute();
$stmt->bind_result($rol);
$stmt->fetch();
$stmt->close();

if ($rol !== 'admin') {
    header("Location: home.php");
    exit();
}

$limit = 20;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

$totalResult = $conn->query("SELECT COUNT(*) AS total FROM solicitudes_prestamos");
$totalRows = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

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
LIMIT $limit OFFSET $offset
";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Administrar Solicitudes</title>
    <link rel="stylesheet" href="../css/banco.css">
    <link rel="stylesheet" href="../css/style_tablas.css">
    <style>
        .pagination {
            margin-top: 20px;
            text-align: center;
            font-family: Arial, sans-serif;
        }

        .pagination a {
            display: inline-block;
            margin: 0 5px;
            padding: 8px 12px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .pagination a:hover {
            background-color: #0056b3;
        }

        .pagination a.active {
            background-color: #0056b3;
            font-weight: bold;
        }

        .pagination a.disabled {
            pointer-events: none;
            opacity: 0.5;
        }
    </style>
</head>

<body class="container mt-4">

    <h2 class="mb-4">Panel de Solicitudes de Préstamos</h2>

    <?php if ($result->num_rows === 0): ?>
        <div class="alert-message">No hay solicitudes registradas aún.</div>
    <?php else: ?>
        <table class="table table-bordered table-hover">
            <thead>
                <tr style="background-color: #0a3d62; color: white;">
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
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) ?></td>
                        <td><?= htmlspecialchars($row['producto']) ?></td>
                        <td>$<?= number_format($row['monto_solicitado'], 2, ',', '.') ?></td>
                        <td>
                            <span class="estado 
                            <?= $row['estado'] == 'pendiente' ? 'estado-pendiente' :
                                ($row['estado'] == 'aprobado' ? 'estado-aprobado' : 'estado-rechazado') ?>">
                                <?= ucfirst($row['estado']) ?>
                            </span>
                        </td>
                        <td><?= date('d/m/Y', strtotime($row['fecha_solicitud'])) ?></td>
                        <td class="d-flex gap-1">
                            <a href="detalle_prestamo.php?id=<?= $row['id'] ?>" class="btn">Detalle</a>
                            <?php if ($row['estado'] === 'aprobado'): ?>
                                <a href="admin_cuotas.php?prestamo_id=<?= $row['id'] ?>" class="btn">Cuotas</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="pagination">
            <a href="?page=<?= max(1, $page - 1) ?>" class="<?= $page == 1 ? 'disabled' : '' ?>">&laquo; Anterior</a>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
            <a href="?page=<?= min($totalPages, $page + 1) ?>"
                class="<?= $page == $totalPages ? 'disabled' : '' ?>">Siguiente &raquo;</a>
        </div>
    <?php endif; ?>

    <div style="text-align:center; margin-top:20px;">
        <a href="home.php" class="btn">Volver al Home</a>
    </div>

</body>

</html>