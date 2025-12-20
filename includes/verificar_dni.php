<?php
if (isset($_GET['dni'])) {
    $dni = $_GET['dni'];
    $conexion = new mysqli("localhost", "root", "", "financiera");

    if ($conexion->connect_error) {
        echo json_encode(['error' => 'Error de conexión']);
        exit;
    }

    $stmt = $conexion->prepare("SELECT COUNT(*) FROM usuarios WHERE dni = ?");
    $stmt->bind_param("s", $dni);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    $conexion->close();

    echo json_encode(['exists' => $count > 0]);
}
?>