<?php
session_start();

// Verificar login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$conexion = new mysqli("localhost", "root", "", "financiera");
if ($conexion->connect_error)
    die("Conexión fallida: " . $conexion->connect_error);

// Obtener datos actuales
$stmt = $conexion->prepare("SELECT nombre, apellido, dni, telefono, provincia, fecha_nacimiento, email FROM usuarios WHERE id=?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($nombre, $apellido, $dni, $telefono, $provincia, $fecha_nacimiento, $email);
$stmt->fetch();
$stmt->close();

// Guardar cambios
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
        $stmt = $conexion->prepare("UPDATE usuarios SET nombre=?, apellido=?, telefono=?, provincia=?, fecha_nacimiento=?, email=?, contrasena=? WHERE id=?");
        $stmt->bind_param("sssssssi", $nombre_new, $apellido_new, $telefono_new, $provincia_new, $fecha_nacimiento_new, $email_new, $pass_hashed, $usuario_id);
    } else {
        $stmt = $conexion->prepare("UPDATE usuarios SET nombre=?, apellido=?, telefono=?, provincia=?, fecha_nacimiento=?, email=? WHERE id=?");
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

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles_home.css">
    <title>Perfil de Usuario</title>
</head>

<body>

    <?php include '../includes/header.php'; ?>

    <!-- Contenido del perfil -->
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
                            <div class="col-md-6 mb-4">
                                <label>Nombre/s:</label>
                                <input type="text" name="nombre" class="form-control form-control-lg"
                                    value="<?= htmlspecialchars($nombre) ?>" required>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label>Apellido/s:</label>
                                <input type="text" name="apellido" class="form-control form-control-lg"
                                    value="<?= htmlspecialchars($apellido) ?>" required>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label>DNI:</label>
                            <input type="text" class="form-control-lg" value="<?= htmlspecialchars($dni) ?>" disabled>
                        </div>

                        <div class="form-group mb-3">
                            <label>Teléfono:</label>
                            <input type="text" name="telefono" class="form-control-lg"
                                value="<?= htmlspecialchars($telefono) ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Provincia:</label>
                            <select name="provincia" class="select-control" required>
                                <option value="">Elegir provincia</option>
                                <option value="Buenos Aires" <?= $provincia == "Buenos Aires" ? "selected" : "" ?>>Buenos
                                    Aires
                                </option>
                                <option value="Córdoba" <?= $provincia == "Córdoba" ? "selected" : "" ?>>Córdoba</option>
                                <option value="Santa Fe" <?= $provincia == "Santa Fe" ? "selected" : "" ?>>Santa Fe
                                </option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>Fecha de nacimiento:</label>
                            <input type="date" name="fechaNacimiento" class="form-control-lg"
                                value="<?= $fecha_nacimiento ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Correo electrónico:</label>
                            <input type="email" name="email" class="form-control-lg"
                                value="<?= htmlspecialchars($email) ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Contraseña (dejar vacío si no se desea cambiar):</label>
                            <input type="password" name="contrasena" class="form-control-lg">
                        </div>

                        <button type="submit" class="btn-register">Actualizar Datos</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <footer>
        <p>&copy; 2025 FinancieraYA. Todos los derechos reservados.</p>
    </footer>

</body>

</html>