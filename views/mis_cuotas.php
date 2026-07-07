<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

/* Obtener todas las cuotas del usuario, con el préstamo y producto */
$sql = "
SELECT 
    sp.id AS prestamo_id,
    pf.nombre AS producto,
    cp.numero_cuota,
    cp.monto,
    cp.fecha_vencimiento,
    cp.estado
FROM cuotas_prestamo cp
JOIN solicitudes_prestamos sp ON cp.prestamo_id = sp.id
JOIN productos_financieros pf ON sp.producto_id = pf.id
WHERE sp.usuario_id = ?
ORDER BY sp.id, cp.numero_cuota
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
    <title>Mis Cuotas - FinancieraYA</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <style>
        .badge {
            font-weight: bold;
        }

        .estado-pagada {
            background-color: #28a745;
            color: white;
        }

        .estado-pendiente {
            background-color: #ffc107;
            color: #212529;
        }

        .estado-vencida {
            background-color: #dc3545;
            color: white;
        }

        .table-container {
            overflow-x: auto;
        }
    </style>
</head>

<body class="container mt-4">

    <h2 class="mb-4">💳 Mis Cuotas</h2>

    <?php if ($result->num_rows === 0): ?>
        <div class="alert alert-info">Todavía no realizaste ninguna solicitud de préstamo.</div>
    <?php else: ?>
        <div class="table-container">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Préstamo / Producto</th>
                        <th>Cuota Nº</th>
                        <th>Monto</th>
                        <th>Vencimiento</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $ultimo_prestamo = null;
                    $hoy = date('Y-m-d');
                    while ($row = $result->fetch_assoc()):
                        // Determinar color del badge
                        if ($row['estado'] === 'pagada') {
                            $badgeClass = 'estado-pagada';
                        } elseif ($row['fecha_vencimiento'] < $hoy) {
                            $badgeClass = 'estado-vencida';
                        } else {
                            $badgeClass = 'estado-pendiente';
                        }
                        ?>
                        <tr>
                            <td>
                                <?php
                                // Mostrar solo una vez el producto por grupo de cuotas
                                if ($ultimo_prestamo !== $row['prestamo_id']) {
                                    echo $row['producto'];
                                    $ultimo_prestamo = $row['prestamo_id'];
                                }
                                ?>
                            </td>
                            <td><?= $row['numero_cuota'] ?></td>
                            <td>$<?= number_format($row['monto'], 2, ',', '.') ?></td>
                            <td><?= date('d/m/Y', strtotime($row['fecha_vencimiento'])) ?></td>
                            <td><span class="badge <?= $badgeClass ?>"><?= ucfirst($row['estado']) ?></span></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <a href="home.php" class="btn btn-secondary mt-3">Volver al Home</a>

</body>

</html>