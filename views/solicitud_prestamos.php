<?php
include '../includes/db.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $monto = $_POST['monto'];
    $tipoEmpleo = $_POST['tipo_empleo'];
    $ingresosMensuales = $_POST['ingresos_mensuales'];
    $motivoPrestamo = $_POST['motivo_prestamo'];

    if (empty($errors)) {
        $sql = "INSERT INTO solicitudes_prestamos (usuario_id, producto_id, monto_solicitado, estado, tipo_empleo, ingresos_mensuales, motivo_prestamo)
                VALUES (1, 1, $monto, 'pendiente', '$tipoEmpleo', $ingresosMensuales, '$motivoPrestamo')";

        if ($conn->query($sql) === TRUE) {
            header("Location: index.php?success=1");
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
    <link rel="stylesheet" href="../css/style.css">
    <title>Solicitud de Préstamo</title>
</head>

<body>

    <div class="container">
        <form>
            <h2>Solicitud de Préstamo</h2>

            <label for="monto">Monto Solicitado:</label>
            <input type="number" id="monto" name="monto" required>

            <label for="plazo">Plazo en meses:</label>
            <input type="number" id="plazo" name="plazo" required>

            <label for="motivo">Motivo del Préstamo:</label>
            <input type="text" id="motivo" name="motivo" required>

            <label for="tipo_empleo">Tipo de Empleo:</label>
            <select id="tipo_empleo" name="tipo_empleo" required>
                <option value="dependiente">Dependiente</option>
                <option value="independiente">Independiente</option>
                <option value="desempleado">Desempleado</option>
                <option value="otros">Otros</option>
            </select>

            <label for="ingresos">Ingresos Mensuales:</label>
            <input type="number" id="ingresos" name="ingresos" required>

            <button type="submit">Enviar Solicitud</button>
        </form>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="error-message">
            <?php foreach ($errors as $error): ?>
                <p>
                    <?php echo $error; ?>
                </p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</body>

</html>