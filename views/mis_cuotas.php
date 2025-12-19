<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

$sql = "
SELECT 
    cp.numero_cuota,
    cp.monto,
    cp.fecha_vencimiento,
    cp.estado
FROM cuotas_prestamos cp
JOIN solicitudes_prestamos sp ON cp.prestamo_id = sp.id
WHERE sp.usuario_id = ?
ORDER BY cp.numero_cuota
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
    <title>Mis Cuotas</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
</head>

<body class="container mt-4">

    <h2>💳 Mis Cuotas</h2>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Cuota</th>
                <th>Monto</th>
                <th>Vencimiento</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>

            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['numero_cuota'] ?></td>
                    <td>$<?= number_format($row['monto'], 2) ?></td>
                    <td><?= $row['fecha_vencimiento'] ?></td>
                    <td>
                        <span class="badge <?= $row['estado'] === 'pagada' ? 'bg-success' : 'bg-warning' ?>">
                            <?= ucfirst($row['estado']) ?>
                        </span>
                    </td>
                </tr>
            <?php endwhile; ?>

        </tbody>
    </table>

    <a href="home.php" class="btn btn-secondary">⬅ Volver</a>

</body>

</html>