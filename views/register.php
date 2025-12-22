<?php
session_start();
include '../includes/db.php';

$errors = [];
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $telefono = $_POST['telefono'];
    $provincia = $_POST['provincia'];
    $fechaNacimiento = $_POST['fechaNacimiento'];
    $email = $_POST['email'];
    $contrasena = $_POST['contrasena'];

    // Verificar si DNI o email ya existen
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE dni=? OR email=?");
    $stmt->bind_param("ss", $dni, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $errors[] = "DNI o Email ya en uso";
    } else {
        $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

        $stmt_insert = $conn->prepare("INSERT INTO usuarios (nombre, apellido, dni, telefono, provincia, fechaNacimiento, email, contrasena, rol) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'usuario')");
        $stmt_insert->bind_param("ssssssss", $nombre, $apellido, $dni, $telefono, $provincia, $fechaNacimiento, $email, $hashed_password);
        if ($stmt_insert->execute()) {
            $success = true;
        } else {
            $errors[] = "Error al registrar el usuario";
        }
        $stmt_insert->close();
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style_register.css">
    <title>Registro de Usuario</title>
</head>

<body>
    <section class="h-100">
        <div class="container py-5">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-center mb-4">Registro de Usuario</h2>

                    <!-- Botones debajo del título -->
                    <div class="btn-container mb-4">
                        <a href="home.php" class="btn btn-secondary">Volver al Home</a>
                        <a href="login.php" class="btn btn-primary">Ya tengo cuenta</a>
                    </div>

                    <!-- Formulario -->
                    <form id="register-form" action="../includes/guardar_usuario.php" method="post">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre/s:</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Apellido/s:</label>
                                <input type="text" name="apellido" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label>DNI:</label>
                            <input type="text" name="dni" id="dni" class="form-control form-control-lg" required>
                            <small id="dni-error" class="text-danger" style="display:none;"></small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Teléfono:</label>
                            <input type="text" name="telefono" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Provincia:</label>
                            <select name="provincia" class="form-control" required>
                                <option value="">Elegir provincia</option>
                                <option value="Buenos Aires">Buenos Aires</option>
                                <option value="Córdoba">Córdoba</option>
                                <option value="Santa Fe">Santa Fe</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Fecha de nacimiento:</label>
                            <input type="date" name="fechaNacimiento" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Correo electrónico:</label>
                            <input type="email" name="email" id="email" class="form-control form-control-lg" required>
                            <small id="email-error" class="text-danger" style="display:none;"></small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contraseña:</label>
                            <input type="password" name="contrasena" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-warning btn-lg mt-3">Registrarse</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="../js/validar_dni_email.js"></script>
</body>

</html>