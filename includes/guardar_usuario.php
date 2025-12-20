<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $telefono = $_POST['telefono'];
    $provincia = $_POST['provincia'];
    $fecha_nacimiento = $_POST['fechaNacimiento'];
    $email = $_POST['email'];
    $pass = $_POST['contrasena'];

    $conexion = new mysqli("localhost", "root", "", "financiera");

    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Verificar email
    $stmt = $conexion->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count_email);
    $stmt->fetch();
    $stmt->close();

    if ($count_email > 0) {
        echo "<script>alert('El email ya está registrado'); window.location='../views/register.php';</script>";
        exit;
    }

    // Verificar DNI
    $stmt = $conexion->prepare("SELECT COUNT(*) FROM usuarios WHERE dni = ?");
    $stmt->bind_param("s", $dni);
    $stmt->execute();
    $stmt->bind_result($count_dni);
    $stmt->fetch();
    $stmt->close();

    if ($count_dni > 0) {
        echo "<script>alert('El DNI ya está registrado'); window.location='../views/register.php';</script>";
        exit;
    }

    // Insertar usuario
    $pass_hash = password_hash($pass, PASSWORD_DEFAULT);

    $stmt = $conexion->prepare("INSERT INTO usuarios 
        (nombre, apellido, dni, telefono, provincia, fecha_nacimiento, email, contrasena, saldo)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)");

    $stmt->bind_param(
        "ssssssss",
        $nombre,
        $apellido,
        $dni,
        $telefono,
        $provincia,
        $fecha_nacimiento,
        $email,
        $pass_hash
    );

    if ($stmt->execute()) {
        echo "<script>alert('Usuario registrado con éxito'); window.location='../views/login.php';</script>";
    } else {
        echo "Error al guardar: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
}
?>
