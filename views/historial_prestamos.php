<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
$sql = "SELECT * FROM solicitudes_prestamos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Historial de Préstamos</title>
</head>

<body>
    <h2>Historial de Préstamos</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>ID de Usuario</th>
            <th>ID de Producto</th>
            <th>Monto Solicitado</th>
            <th>Estado</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>DNI</th>
            <th>Tipo de Empleo</th>
            <th>Ingresos Mensuales</th>
            <th>Motivo del Préstamo</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td>
                    <?php echo $row['id']; ?>
                </td>
                <td>
                    <?php echo $row['usuario_id']; ?>
                </td>
                <td>
                    <?php echo $row['producto_id']; ?>
                </td>
                <td>
                    <?php echo $row['monto_solicitado']; ?>
                </td>
                <td>
                    <?php echo $row['estado']; ?>
                </td>
                <td>
                    <?php echo $row['nombre']; ?>
                </td>
                <td>
                    <?php echo $row['apellido']; ?>
                </td>
                <td>
                    <?php echo $row['dni']; ?>
                </td>
                <td>
                    <?php echo $row['tipo_empleo']; ?>
                </td>
                <td>
                    <?php echo $row['ingresos_mensuales']; ?>
                </td>
                <td>
                    <?php echo $row['motivo_prestamo']; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>