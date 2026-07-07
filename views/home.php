<?php
session_start();
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

    <!-- Header -->
    <?php include '../includes/header.php'; ?>

    <!-- Mensaje de sesión -->
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="mensaje">
            <?= htmlspecialchars($_SESSION['mensaje']) ?>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <!-- Contenedor principal -->
    <div class="container">

        <!-- Hero -->
        <section id="hero" class="section-hero">
            <h1 class="titulo-principal">Bienvenido a FinancieraYA</h1>
            <p>Soluciones financieras diseñadas para vos.</p>
            <a href="../views/solicitud_prestamos.php" class="btn btn-solicitar">Solicitar Préstamo</a>
        </section>

        <!-- Sección Acerca de -->
        <section id="acerca" class="card section">
            <h2 class="titulo-seccion">Acerca de nosotros</h2>
            <p>
                Brindamos servicios financieros accesibles, rápidos y confiables
                para ayudarte a crecer. Nuestra misión es acompañarte en cada paso
                de tu camino financiero.
            </p>
        </section>

        <!-- Sección Contacto rápida -->
        <section id="contacto-rapido" class="card section">
            <h2 class="titulo-seccion">Contacto</h2>
            <p>Podés escribirnos o llamarnos si tenés dudas o consultas.</p>
            <p><strong>Email:</strong> contacto@financieraya.com</p>
            <p><strong>Teléfono:</strong> +54 9 11 1234-5678</p>
            <a href="../views/contacto.php" class="btn btn-contacto">Ir a Contacto</a>
        </section>

    </div>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

</body>

</html>