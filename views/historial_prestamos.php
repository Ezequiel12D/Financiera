<?php
session_start();
include '../includes/db.php';

/* Verificar login */
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

/* Obtener solicitudes del usuario con cuotas pagadas y próximo vencimiento */
$sql = "
SELECT 
    sp.id,
    pf.nombre AS producto,
    sp.monto_solicitado,
    sp.monto_total,
    sp.cuota_mensual,
    sp.estado,
    sp.plazo_meses,
    sp.fecha_solicitud,
    (
        SELECT COUNT(*) 
        FROM cuotas_prestamo c 
        WHERE c.prestamo_id = sp.id AND c.estado = 'pagada'
    ) AS cuotas_pagadas,
    (
        SELECT MIN(fecha_vencimiento)
        FROM cuotas_prestamo c 
        WHERE c.prestamo_id = sp.id AND c.estado = 'pendiente'
    ) AS proximo_vencimiento
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
    <link rel="stylesheet" href="../css/banco.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: #0a3d62;
            color: white;
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 12px;
            font-weight: bold;
        }

        .estado-pendiente {
            background-color: #fff3cd;
            color: #856404;
        }

        .estado-aprobado {
            background-color: #d4edda;
            color: #155724;
        }

        .estado-rechazado {
            background-color: #f8d7da;
            color: #721c24;
        }

        .btn-home {
            display: block;
            background-color: #0a3d62;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            width: 200px;
            margin: 20px auto 0 auto;
            text-align: center;
            transition: background 0.3s;
        }

        .btn-home:hover {
            background-color: #094074;
        }

        @media (max-width: 768px) {

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }

            table th {
                text-align: right;
            }

            table td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            table td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                font-weight: bold;
            }
        }
    </style>
</head>

<body class="container">

    <h2>Mis Solicitudes de Préstamo</h2>

    <?php if ($result->num_rows === 0): ?>
        <div class="alert mensaje">
            Todavía no realizaste ninguna solicitud.
        </div>
    <?php else: ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Monto</th>
                    <th>Plazo</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Cuota</th>
                    <th>Cuotas Pagadas</th>
                    <th>Próximo Vencimiento</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td data-label="ID"><?= $row['id'] ?></td>
                        <td data-label="Producto"><?= $row['producto'] ?></td>
                        <td data-label="Monto">$<?= number_format($row['monto_solicitado'], 2, ',', '.') ?></td>
                        <td data-label="Plazo"><?= $row['plazo_meses'] ?> meses</td>
                        <td data-label="Estado">
                            <span class="badge 
                                <?= $row['estado'] === 'pendiente' ? 'estado-pendiente' :
                                    ($row['estado'] === 'aprobado' ? 'estado-aprobado' : 'estado-rechazado') ?>">
                                <?= ucfirst($row['estado']) ?>
                            </span>
                        </td>
                        <td data-label="Fecha"><?= $row['fecha_solicitud'] ?></td>
                        <td data-label="Total">
                            <?= $row['monto_total'] ? '$' . number_format($row['monto_total'], 2, ',', '.') : '-' ?>
                        </td>
                        <td data-label="Cuota">
                            <?= $row['cuota_mensual'] ? '$' . number_format($row['cuota_mensual'], 2, ',', '.') : '-' ?>
                        </td>
                        <td data-label="Cuotas Pagadas"><?= $row['cuotas_pagadas'] ?></td>
                        <td data-label="Próximo Vencimiento">
                            <?= $row['proximo_vencimiento'] ? $row['proximo_vencimiento'] : '-' ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    <?php endif; ?>

    <a href="home.php" class="btn-home">Volver al Home</a>

</body>

</html>