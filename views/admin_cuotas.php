<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: home.php");
    exit();
}


$prestamo_id = isset($_GET['prestamo_id']) ? intval($_GET['prestamo_id']) : 0;

if ($prestamo_id <= 0) {
    header("Location: admin_solicitudes.php");
    exit();
}

$limit = 20;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

$totalResult = $conn->query("
    SELECT COUNT(*) AS total
    FROM cuotas_prestamo
    WHERE prestamo_id = $prestamo_id
");
$totalRows = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

$sql = "
SELECT 
    cp.id AS cuota_id,
    cp.numero_cuota,
    cp.monto,
    cp.fecha_vencimiento,
    cp.estado,
    u.nombre,
    u.apellido,
    pf.nombre AS producto,
    (
        SELECT COUNT(*)
        FROM cuotas_prestamo c2
        WHERE c2.prestamo_id = cp.prestamo_id
        AND c2.estado = 'pagada'
    ) AS cuotas_pagadas
FROM cuotas_prestamo cp
JOIN solicitudes_prestamos sp ON cp.prestamo_id = sp.id
JOIN usuarios u ON sp.usuario_id = u.id
JOIN productos_financieros pf ON sp.producto_id = pf.id
WHERE cp.prestamo_id = $prestamo_id
ORDER BY cp.numero_cuota
LIMIT $limit OFFSET $offset
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Administración de Cuotas</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>

    <?php include '../includes/header.php'; ?>

    <div class="container">
        <h2>Administración de Cuotas</h2>

        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Producto</th>
                    <th>Cuota Nº</th>
                    <th>Monto</th>
                    <th>Cuotas Pagadas</th>
                    <th>Vencimiento</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) ?></td>
                        <td><?= htmlspecialchars($row['producto']) ?></td>
                        <td><?= $row['numero_cuota'] ?></td>
                        <td>$<?= number_format($row['monto'], 2, ',', '.') ?></td>
                        <td><?= $row['cuotas_pagadas'] ?></td>
                        <td><?= date('d/m/Y', strtotime($row['fecha_vencimiento'])) ?></td>
                        <td>
                            <span
                                class="estado <?= $row['estado'] === 'pagada' ? 'estado-aprobado' : 'estado-pendiente' ?>">
                                <?= ucfirst($row['estado']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($row['estado'] === 'pendiente'): ?>
                                <form action="../includes/marcar_cuota_pagada.php" method="post">
                                    <input type="hidden" name="cuota_id" value="<?= $row['cuota_id'] ?>">
                                    <button type="submit" class="btn aprobar">Marcar Pagada</button>
                                </form>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="pagination">
            <a href="?prestamo_id=<?= $prestamo_id ?>&page=<?= max(1, $page - 1) ?>"
                class="<?= $page == 1 ? 'disabled' : '' ?>">&laquo; Anterior</a>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?prestamo_id=<?= $prestamo_id ?>&page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <a href="?prestamo_id=<?= $prestamo_id ?>&page=<?= min($totalPages, $page + 1) ?>"
                class="<?= $page == $totalPages ? 'disabled' : '' ?>">
                Siguiente &raquo;
            </a>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

</body>

</html>