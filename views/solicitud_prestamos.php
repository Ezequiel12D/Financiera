<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['mensaje'] = "Debes iniciar sesión o registrarte para solicitar un préstamo.";
    header("Location: login.php");
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

    if (!$producto_id)
        $errors[] = "Debes seleccionar un producto financiero.";
    if ($monto <= 0 || $monto > 750000)
        $errors[] = "El monto debe ser mayor a 0 y máximo $750.000.";

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/solicitudes.css">
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="text-center mb-4">Solicitud de Préstamo</h3>

                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <?php foreach ($errors as $e): ?>
                                    <p class="mb-1"><?= $e ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <form method="post">
                            <div class="mb-3">
                                <label for="producto_id" class="form-label">Producto financiero:</label>
                                <select name="producto_id" id="producto_id" class="form-select" required>
                                    <option value="" disabled selected>Seleccionar producto</option>
                                    <?php while ($p = $productos->fetch_assoc()): ?>
                                        <option value="<?= $p['id'] ?>"><?= $p['nombre'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="monto" class="form-label">Monto (máx $750.000):</label>
                                <input type="number" id="monto" name="monto" class="form-control" max="750000" min="1"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="tipo_empleo" class="form-label">Tipo de empleo:</label>
                                <select name="tipo_empleo" id="tipo_empleo" class="form-select" required>
                                    <option value="relacion_dependencia">Relación de dependencia</option>
                                    <option value="relacion_independiente">Independiente</option>
                                    <option value="otros">Otros</option>
                                    <option value="sin_empleo">Sin empleo</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="ingresos_mensuales" class="form-label">Ingresos mensuales:</label>
                                <input type="number" id="ingresos_mensuales" name="ingresos_mensuales"
                                    class="form-control" min="0" required>
                            </div>

                            <div class="mb-3">
                                <label for="motivo_prestamo" class="form-label">Motivo del préstamo:</label>
                                <textarea id="motivo_prestamo" name="motivo_prestamo" class="form-control" rows="3"
                                    placeholder="Escribe el motivo del préstamo" style="resize: none;"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="plazo" class="form-label">Plazo:</label>
                                <select name="plazo" id="plazo" class="form-select" required>
                                    <option value="12">12 meses</option>
                                    <option value="24">24 meses</option>
                                    <option value="36">36 meses</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Enviar Solicitud</button>
                            <a href="home.php" class="btn btn-secondary w-100 mt-2">Volver al Home</a>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>