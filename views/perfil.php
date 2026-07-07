<?php
session_start();
include '../includes/db.php';

// Verificar login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// --- Datos del usuario ---
$stmt = $conn->prepare("SELECT nombre, apellido, dni, telefono, provincia, fecha_nacimiento, email FROM usuarios WHERE id=?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($nombre, $apellido, $dni, $telefono, $provincia, $fecha_nacimiento, $email);
$stmt->fetch();
$stmt->close();

// --- Actualizar datos ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_new = $_POST['nombre'];
    $apellido_new = $_POST['apellido'];
    $telefono_new = $_POST['telefono'];
    $provincia_new = $_POST['provincia'];
    $fecha_nacimiento_new = $_POST['fechaNacimiento'];
    $email_new = $_POST['email'];
    $pass_new = $_POST['contrasena'];

    if (!empty($pass_new)) {
        $pass_hashed = password_hash($pass_new, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET nombre=?, apellido=?, telefono=?, provincia=?, fecha_nacimiento=?, email=?, contrasena=? WHERE id=?");
        $stmt->bind_param("sssssssi", $nombre_new, $apellido_new, $telefono_new, $provincia_new, $fecha_nacimiento_new, $email_new, $pass_hashed, $usuario_id);
    } else {
        $stmt = $conn->prepare("UPDATE usuarios SET nombre=?, apellido=?, telefono=?, provincia=?, fecha_nacimiento=?, email=? WHERE id=?");
        $stmt->bind_param("ssssssi", $nombre_new, $apellido_new, $telefono_new, $provincia_new, $fecha_nacimiento_new, $email_new, $usuario_id);
    }

    $stmt->execute();
    $mensaje = "Datos actualizados correctamente.";
    $stmt->close();

    // Actualizar variables para mostrar cambios
    $nombre = $nombre_new;
    $apellido = $apellido_new;
    $telefono = $telefono_new;
    $provincia = $provincia_new;
    $fecha_nacimiento = $fecha_nacimiento_new;
    $email = $email_new;
}

// --- Solicitudes de préstamos ---
$sql_solicitudes = "
SELECT sp.id, pf.nombre AS producto, sp.monto_solicitado, sp.monto_total, sp.cuota_mensual, sp.estado, sp.plazo_meses, sp.fecha_solicitud
FROM solicitudes_prestamos sp
JOIN productos_financieros pf ON sp.producto_id = pf.id
WHERE sp.usuario_id = ?
ORDER BY sp.fecha_solicitud DESC
";
$stmt = $conn->prepare($sql_solicitudes);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result_solicitudes = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles_home.css">
    <link rel="stylesheet" href="../css/banco.css">
    <title>Mi Perfil - FinancieraYA</title>
    <style>
        .table-custom {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table-custom th,
        .table-custom td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: center;
        }

        .table-custom th {
            background-color: #0a3d62;
            color: white;
        }

        .badge {
            padding: 5px 10px;
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
    </style>
</head>

<body>

    <?php include '../includes/header.php'; ?>

    <section class="registration-section">
        <div class="container py-5">
            <div class="card card-registration">
                <div class="card-body">
                    <h3 class="text-uppercase">Mi Perfil</h3>

                    <?php if (isset($mensaje)): ?>
                        <div class="alert-message"><?= $mensaje ?></div>
                    <?php endif; ?>

                    <form method="post">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Nombre/s:</label>
                                <input type="text" name="nombre" class="form-control"
                                    value="<?= htmlspecialchars($nombre) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Apellido/s:</label>
                                <input type="text" name="apellido" class="form-control"
                                    value="<?= htmlspecialchars($apellido) ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>DNI:</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($dni) ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label>Teléfono:</label>
                            <input type="text" name="telefono" class="form-control"
                                value="<?= htmlspecialchars($telefono) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Provincia:</label>
                            <select name="provincia" class="form-control" required>
                                <option value="">Elegir provincia</option>
                                <option value="Buenos Aires" <?= $provincia == "Buenos Aires" ? "selected" : "" ?>>Buenos Aires
                                </option>
                                <option value="Córdoba" <?= $provincia == "Córdoba" ? "selected" : "" ?>>Córdoba</option>
                                <option value="Santa Fe" <?= $provincia == "Santa Fe" ? "selected" : "" ?>>Santa Fe</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Fecha de nacimiento:</label>
                            <input type="date" name="fechaNacimiento" class="form-control"
                                value="<?= $fecha_nacimiento ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Correo electrónico:</label>
                            <input type="email" name="email" class="form-control"
                                value="<?= htmlspecialchars($email) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Contraseña (dejar vacío si no se desea cambiar):</label>
                            <input type="password" name="contrasena" class="form-control">
                        </div>
                        <button type="submit" class="btn-register">Actualizar Datos</button>
                    </form>
                </div>
            </div>

            <!-- Mis Préstamos -->
            <h3 class="mt-5">Mis Solicitudes de Préstamo</h3>
            <?php if ($result_solicitudes->num_rows == 0): ?>
                <p>No tenés solicitudes registradas aún.</p>
            <?php else: ?>
                <table class="table-custom">
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result_solicitudes->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= $row['producto'] ?></td>
                                <td>$<?= number_format($row['monto_solicitado'], 2, ',', '.') ?></td>
                                <td><?= $row['plazo_meses'] ?> meses</td>
                                <td>
                                    <span
                                        class="badge <?= $row['estado'] == "pendiente" ? "estado-pendiente" : ($row['estado'] == "aprobado" ? "estado-aprobado" : "estado-rechazado") ?>">
                                        <?= ucfirst($row['estado']) ?>
                                    </span>
                                </td>
                                <td><?= $row['fecha_solicitud'] ?></td>
                                <td><?= $row['monto_total'] ? "$" . number_format($row['monto_total'], 2, ',', '.') : "-" ?></td>
                                <td><?= $row['cuota_mensual'] ? "$" . number_format($row['cuota_mensual'], 2, ',', '.') : "-" ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 FinancieraYA. Todos los derechos reservados.</p>
    </footer>

</body>

</html>