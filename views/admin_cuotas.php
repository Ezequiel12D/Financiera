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

/* Obtener préstamos aprobados */
$sql = "
SELECT 
    sp.id AS prestamo_id,
    u.nombre,
    u.apellido,
    pf.nombre AS producto,
    sp.monto_solicitado,
    pf.tasa_interes,
    sp.plazo_meses,
    sp.estado,
    sp.fecha_solicitud
FROM solicitudes_prestamos sp
JOIN usuarios u ON sp.usuario_id = u.id
JOIN productos_financieros pf ON sp.producto_id = pf.id
WHERE sp.estado = 'aprobado'
ORDER BY sp.fecha_solicitud DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Cuotas y Préstamos</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
</head>

<body class="container mt-4">

    <h2 class="mb-4">📊 Cuotas de Préstamos Aprobados</h2>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Cliente</th>
                <th>Producto</th>
                <th>Monto</th>
                <th>Tasa</th>
                <th>Plazo</th>
                <th>Cuota</th>
                <th>Total a pagar</th>
            </tr>
        </thead>
        <tbody>

            <?php while ($row = $result->fetch_assoc()): ?>

                <?php
                $monto = $row['monto_solicitado'];
                $tasa = $row['tasa_interes'] / 100;
                $plazo = $row['plazo_meses'];

                $interes_total = $monto * $tasa;
                $total_pagar = $monto + $interes_total;
                $cuota = $total_pagar / $plazo;
                ?>

                <tr>
                    <td><?= $row['nombre'] . " " . $row['apellido'] ?></td>
                    <td><?= $row['producto'] ?></td>
                    <td>$<?= number_format($monto, 2, ',', '.') ?></td>
                    <td><?= $row['tasa_interes'] ?>%</td>
                    <td><?= $plazo ?> meses</td>
                    <td>$<?= number_format($cuota, 2, ',', '.') ?></td>
                    <td>$<?= number_format($total_pagar, 2, ',', '.') ?></td>
                </tr>

            <?php endwhile; ?>

        </tbody>
    </table>

    <a href="home.php" class="btn btn-secondary mt-3">⬅ Volver al Home</a>

</body>

</html>