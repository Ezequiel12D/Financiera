<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $contraseña = $_POST['contraseña'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $dni = $_POST['dni'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $provincia = $_POST['provincia'] ?? '';
    $fecha_nacimiento = $_POST['fechaNacimiento'] ?? '';
    $conexion = new mysqli("localhost", "root", "", "financiera");

    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }
    echo "Correo electrónico ingresado: " . $email;

    $checkEmailQuery = "SELECT COUNT(*) as count FROM usuarios WHERE email = '$email'";
    $result = $conexion->query($checkEmailQuery);
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        echo '<p style="color: red;">Error: El correo electrónico ya está registrado</p>';
    } else {
        $sql = "INSERT INTO usuarios (nombre, email, `contraseña`, apellido, dni, telefono, provincia, fecha_nacimiento) VALUES ('$nombre', '$email', '$contraseña', '$apellido', '$dni', '$telefono', '$provincia', '$fecha_nacimiento')";

        $resultado = $conexion->query($sql);

    }

    $conexion->close(); 
    header("Location:../views/home.php");
}
?>