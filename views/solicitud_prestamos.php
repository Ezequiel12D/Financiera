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

    if ($monto <= 0) {
        $errors[] = "El monto no es válido.";
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
            header("Location: solicitud_prestamos.php?success=1");
            exit();
        } else {
            $errors[] = "Error al guardar la solicitud.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Solicitud de Préstamo</title>
    <link href="../css/style_solicitud.css" rel="stylesheet">
</head>

<body>

    <h2>Solicitud de Préstamo</h2>

    <?php if (isset($_GET['success'])): ?>
        <script>alert("Solicitud enviada correctamente. Un asesor se comunicará contigo.");</script>
    <?php endif; ?>

    <form method="post">

        <label>Producto financiero:</label>
        <select name="producto_id" required>
            <option value="" disabled selected>Seleccionar producto</option>
            <?php while ($p = $productos->fetch_assoc()): ?>
                <option value="<?= $p['id'] ?>">
                    <?= $p['nombre'] ?>
                </option>
            <?php endwhile; ?>
        </select><br>

        <label>Monto (máx $750.000):</label>
        <input type="number" name="monto" max="750000" required><br>

        <label>Tipo de empleo:</label>
        <select name="tipo_empleo" required>
            <option value="relacion_dependencia">Relación de dependencia</option>
            <option value="relacion_independiente">Independiente</option>
            <option value="otros">Otros</option>
            <option value="sin_empleo">Sin empleo</option>
        </select><br>

        <label>Ingresos mensuales:</label>
        <input type="number" name="ingresos_mensuales" required><br>

        <label>Motivo del préstamo:</label>
        <textarea name="motivo_prestamo" required></textarea><br>

        <label>Plazo:</label>
        <select name="plazo" required>
            <option value="12">12 meses</option>
            <option value="24">24 meses</option>
            <option value="36">36 meses</option>
        </select><br>

        <button type="submit">Enviar Solicitud</button>

        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <?php foreach ($errors as $e): ?>
                    <p><?= $e ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </form>

    <div style="margin-top:20px">
        <a href="home.php" style="
        background:#6c757d;
        padding:10px 20px;
        border-radius:6px;
        color:white;
        text-decoration:none;
        font-weight:bold;
    "> Volver al Home</a>
    </div>

</body>

</html>