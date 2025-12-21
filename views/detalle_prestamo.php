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
    <link rel="stylesheet" href="../css/banco.css">
</head>

<body>

    <header class="topbar">
        <h1>Panel de Préstamos</h1>
    </header>

    <main class="container">

        <h2>Detalle de la Solicitud</h2>

        <div class="card">

            <div class="section">
                <h3>Datos del Cliente</h3>

                <div class="row">
                    <div>
                        <span>Nombre</span>
                        <p><?= $prestamo['nombre'] . ' ' . $prestamo['apellido'] ?></p>
                    </div>
                    <div>
                        <span>Email</span>
                        <p><?= $prestamo['email'] ?></p>
                    </div>
                </div>
            </div>

            <div class="section">
                <h3>Datos del Préstamo</h3>

                <div class="row">
                    <div>
                        <span>Producto</span>
                        <p><?= $prestamo['producto'] ?></p>
                    </div>
                    <div>
                        <span>Monto</span>
                        <p>$<?= number_format($prestamo['monto_solicitado'], 2, ',', '.') ?></p>
                    </div>
                    <div>
                        <span>Plazo</span>
                        <p><?= $prestamo['plazo_meses'] ?> meses</p>
                    </div>
                    <div>
                        <span>Ingresos</span>
                        <p>$<?= number_format($prestamo['ingresos_mensuales'], 2, ',', '.') ?></p>
                    </div>
                    <div>
                        <span>Tipo de empleo</span>
                        <p><?= ucfirst($prestamo['tipo_empleo']) ?></p>
                    </div>
                    <div>
                        <span>Estado</span>
                        <p class="estado estado-<?= $prestamo['estado'] ?>">
                            <?= ucfirst($prestamo['estado']) ?>
                        </p>
                    </div>
                </div>

                <div class="motivo">
                    <span>Motivo del préstamo</span>
                    <p><?= $prestamo['motivo_prestamo'] ?></p>
                </div>

                <p class="fecha">
                    Fecha de solicitud: <?= $prestamo['fecha_solicitud'] ?>
                </p>
            </div>

            <?php if ($prestamo['estado'] === 'pendiente'): ?>
                <form method="post" action="../includes/cambiar_estado.php" class="acciones">
                    <input type="hidden" name="id" value="<?= $prestamo['id'] ?>">

                    <button type="submit" name="estado" value="aprobado" class="btn aprobar">
                        Aprobar
                    </button>

                    <button type="submit" name="estado" value="rechazado" class="btn rechazar">
                        Rechazar
                    </button>
                </form>
            <?php else: ?>
                <p class="procesado">Esta solicitud ya fue procesada.</p>
            <?php endif; ?>

            <a href="admin_solicitudes.php" class="volver"> Volver al panel</a>

        </div>

    </main>

</body>

</html>