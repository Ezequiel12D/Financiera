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

    $stmt = $conexion->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo "<script>alert('El email ya está registrado'); window.location='../views/register.php';</script>";
        exit;
    }

    $stmt = $conexion->prepare("INSERT INTO usuarios 
    (nombre, apellido, dni, telefono, provincia, fecha_nacimiento, email, contrasena, saldo)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)");

    $pass = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

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
        $pass
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