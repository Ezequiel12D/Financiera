<?php
session_start();

if (isset($_SESSION['mensaje'])) {
    echo "<div class='mensaje'>{$_SESSION['mensaje']}</div>";
    unset($_SESSION['mensaje']);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinancieraYA</title>
    <link rel="stylesheet" href="../css/styles_home.css">
</head>

<body>
    <?php include '../includes/header.php'; ?>
    <!-- Mensaje de sesión -->
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="mensaje">
            <?php
            echo $_SESSION['mensaje'];
            unset($_SESSION['mensaje']);
            ?>
        </div>
    <?php endif; ?>

    <!-- Contenedor principal -->
    <div class="container">

        <!-- Hero -->
        <section id="hero">
            <h1 class="titulo-principal">Bienvenido a FinancieraYA</h1>
            <p>Soluciones financieras diseñadas para vos.</p>
            <a href="../views/solicitud_prestamos.php" class="btn-solicitar">Solicitar Préstamo</a>
        </section>

        <!-- Sección Acerca de -->
        <div class="card section">
            <h2 class="titulo-seccion">Acerca de nosotros</h2>
            <p>Brindamos servicios financieros accesibles, rápidos y confiables para ayudarte a crecer.</p>
        </div>

    </div>

   <?php include '../includes/footer.php'; ?>


</body>

</html>