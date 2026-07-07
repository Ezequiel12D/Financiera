<?php
session_start();
include '../includes/db.php';

/* ===============================
   Seguridad: Solo admin
================================ */
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: home.php");
    exit();
}

/* ===============================
   Validar ID de préstamo
================================ */
if (!isset($_GET['id'])) {
    header("Location: admin_solicitudes.php");
    exit();
}

$prestamo_id = intval($_GET['id']);

/* ===============================
   Preparar CSRF token
================================ */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/* ===============================
   Consulta del préstamo
================================ */
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
    <style>
        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
        }

        .card {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .section {
            margin-bottom: 20px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .row>div {
            flex: 1 1 200px;
        }

        .estado {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
            display: inline-block;
        }

        .estado-pendiente {
            background-color: #f0ad4e;
        }

        .estado-aprobado {
            background-color: #5cb85c;
        }

        .estado-rechazado {
            background-color: #d9534f;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
            color: white;
        }

        .btn.aprobar {
            background-color: #5cb85c;
        }

        .btn.rechazar {
            background-color: #d9534f;
        }

        .procesado {
            font-style: italic;
            color: #555;
        }

        .volver {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
        }

        .volver:hover {
            text-decoration: underline;
        }
    </style>
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
                        <p><?= htmlspecialchars($prestamo['nombre'] . ' ' . $prestamo['apellido']) ?></p>
                    </div>
                    <div>
                        <span>Email</span>
                        <p><?= htmlspecialchars($prestamo['email']) ?></p>
                    </div>
                </div>
            </div>

            <div class="section">
                <h3>Datos del Préstamo</h3>
                <div class="row">
                    <div>
                        <span>Producto</span>
                        <p><?= htmlspecialchars($prestamo['producto']) ?></p>
                    </div>
                    <div>
                        <span>Monto</span>
                        <p>$<?= number_format($prestamo['monto_solicitado'], 2, ',', '.') ?></p>
                    </div>
                    <div>
                        <span>Plazo</span>
                        <p><?= intval($prestamo['plazo_meses']) ?> meses</p>
                    </div>
                    <div>
                        <span>Ingresos</span>
                        <p>$<?= number_format($prestamo['ingresos_mensuales'], 2, ',', '.') ?></p>
                    </div>
                    <div>
                        <span>Tipo de empleo</span>
                        <p><?= htmlspecialchars(ucfirst($prestamo['tipo_empleo'])) ?></p>
                    </div>
                    <div>
                        <span>Estado</span>
                        <p class="estado estado-<?= htmlspecialchars($prestamo['estado']) ?>">
                            <?= htmlspecialchars(ucfirst($prestamo['estado'])) ?>
                        </p>
                    </div>
                </div>

                <div class="motivo">
                    <span>Motivo del préstamo</span>
                    <p><?= htmlspecialchars($prestamo['motivo_prestamo']) ?></p>
                </div>

                <p class="fecha">
                    Fecha de solicitud: <?= date('d/m/Y', strtotime($prestamo['fecha_solicitud'])) ?>
                </p>
            </div>

            <?php if ($prestamo['estado'] === 'pendiente'): ?>
                <form method="post" action="../includes/cambiar_estado.php" class="acciones">
                    <input type="hidden" name="id" value="<?= $prestamo['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

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