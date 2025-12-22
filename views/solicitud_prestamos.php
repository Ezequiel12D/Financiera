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

$productos = $conn->query("SELECT id, nombre FROM productos_financieros");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $producto_id = $_POST['producto_id'] ?? null;
    $monto = $_POST['monto'] ?? null;
    $tipoEmpleo = $_POST['tipo_empleo'] ?? '';
    $ingresosMensuales = $_POST['ingresos_mensuales'] ?? null;
    $motivoPrestamo = $_POST['motivo_prestamo'] ?? '';
    $plazoMeses = $_POST['plazo'] ?? null;

    $usuario_id = $_SESSION['usuario_id'];

    if (!$producto_id) {
        $errors[] = "Debes seleccionar un producto financiero.";
    }

    if ($monto <= 0 || $monto > 750000) {
        $errors[] = "El monto debe ser mayor a 0 y máximo $750.000.";
    }

    if (empty($errors)) {

        $stmt = $conn->prepare("
            INSERT INTO solicitudes_prestamos 
            (usuario_id, producto_id, monto_solicitado, estado, tipo_empleo, ingresos_mensuales, motivo_prestamo, plazo_meses)
            VALUES (?, ?, ?, 'pendiente', ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "iidsssi",
            $usuario_id,
            $producto_id,
            $monto,
            $tipoEmpleo,
            $ingresosMensuales,
            $motivoPrestamo,
            $plazoMeses
        );

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Solicitud enviada correctamente. Un asesor se comunicará contigo.";
            header("Location: home.php");
            exit();
        } else {
            $errors[] = "Error al guardar la solicitud. Intenta nuevamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Préstamo</title>
    <link rel="stylesheet" href="../css/style_solicitud.css">
</head>

<body>

    <form method="post">
        <h2>Solicitud de Préstamo</h2>

        <!-- Mensajes de error -->
        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <?php foreach ($errors as $e): ?>
                    <p><?= $e ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <label for="producto_id">Producto financiero:</label>
        <select name="producto_id" id="producto_id" required>
            <option value="" disabled selected>Seleccionar producto</option>
            <?php while ($p = $productos->fetch_assoc()): ?>
                <option value="<?= $p['id'] ?>"><?= $p['nombre'] ?></option>
            <?php endwhile; ?>
        </select>

        <label for="monto">Monto (máx $750.000):</label>
        <input type="number" id="monto" name="monto" max="750000" required>

        <label for="tipo_empleo">Tipo de empleo:</label>
        <select name="tipo_empleo" id="tipo_empleo" required>
            <option value="relacion_dependencia">Relación de dependencia</option>
            <option value="relacion_independiente">Independiente</option>
            <option value="otros">Otros</option>
            <option value="sin_empleo">Sin empleo</option>
        </select>

        <label for="ingresos_mensuales">Ingresos mensuales:</label>
        <input type="number" id="ingresos_mensuales" name="ingresos_mensuales" required>

        <label for="motivo_prestamo">Motivo del préstamo:</label>
        <textarea id="motivo_prestamo" name="motivo_prestamo" rows="3" placeholder="Escribe el motivo del préstamo"
            style="resize: none;"></textarea>




        <label for="plazo">Plazo:</label>
        <select name="plazo" id="plazo" required>
            <option value="12">12 meses</option>
            <option value="24">24 meses</option>
            <option value="36">36 meses</option>
        </select>

        <button type="submit">Enviar Solicitud</button>

        <a href="home.php" style="
            display:block;
            text-align:center;
            margin-top:15px;
            color:white;
            background:#6c757d;
            padding:10px;
            border-radius:4px;
            text-decoration:none;
        ">Volver al Home</a>

    </form>

</body>

</html>