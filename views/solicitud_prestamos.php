<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['usuario_id'])) {
    echo "<script>
            alert('Debes iniciar sesión o registrarte para solicitar un préstamo.');
            window.location.href = 'login.php';
          </script>";
    exit();
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST['nombre'];
    $monto = $_POST['monto'];
    $tipoEmpleo = $_POST['tipo_empleo'];
    $ingresosMensuales = $_POST['ingresos_mensuales'];
    $motivoPrestamo = $_POST['motivo_prestamo'];
    $plazoMeses = $_POST['plazo'];

    $usuario_id = $_SESSION['usuario_id'];

    if (empty($errors)) {

        $sql = "INSERT INTO solicitudes_prestamos 
                (usuario_id, producto_id, monto_solicitado, estado, tipo_empleo, ingresos_mensuales, motivo_prestamo, plazo_meses)
                VALUES ($usuario_id, 1, $monto, 'pendiente', '$tipoEmpleo', $ingresosMensuales, '$motivoPrestamo', $plazoMeses)";

        if ($conn->query($sql) === TRUE) {
            header("Location: solicitud_prestamos.php?success=1"); // ← corregido
            exit();
        } else {
            $errors[] = "Error al realizar la solicitud: " . $conn->error;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/style_solicitud.css" rel="stylesheet" />
    <title>Solicitud de Préstamo</title>
</head>

<body>

    <h2>Solicitud de Préstamo</h2>

    <?php if (isset($_GET['success'])): ?>
        <script>alert("Solicitud enviada correctamente. Un asesor se comunicará contigo.");</script>
    <?php endif; ?>

    <form method="post" action="solicitud_prestamo.php">

        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required><br>

        <label for="monto">Monto (máximo $750.000):</label>
        <input type="number" name="monto" max="750000" required><br>

        <label for="tipo_empleo">Tipo de Empleo:</label>
        <select name="tipo_empleo" required>
            <option value="relacion_dependencia">Relación de Dependencia</option>
            <option value="relacion_independiente">Relación Independiente</option>
            <option value="otros">Otros</option>
            <option value="sin_empleo">No tengo empleo</option>
        </select><br>

        <label for="ingresos_mensuales">Ingresos Mensuales:</label>
        <input type="number" name="ingresos_mensuales" required><br>

        <label for="motivo_prestamo">Motivo del Préstamo:</label>
        <textarea name="motivo_prestamo" rows="4" required></textarea><br>

        <label for="plazo">Plazo en meses:</label>
        <select name="plazo" required>
            <option value="12">12 meses</option>
            <option value="24">24 meses</option>
            <option value="36">36 meses</option>
        </select><br>

        <button type="submit">Enviar Solicitud</button>
        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <?php foreach ($errors as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div style="margin: 20px;">
            <a href="home.php" style="
            background:#6c757d;
            padding:10px 20px;
            border-radius:6px;
            color:white;
            text-decoration:none;
            font-weight:bold;
       ">
                Volver al Home
            </a>
        </div>
    </form>
</body>

</html>