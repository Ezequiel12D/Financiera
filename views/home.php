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
    <link rel="stylesheet" href="../css/styles_home.css">

    <title>FinancieraYA</title>
</head>

<body>

    <header class="main-header">
        <div class="header-container">

            <h1 class="logo">FinancieraYA</h1>

            <nav>
                <ul class="nav-links">
                    <li><a href="home.php">Inicio</a></li>
                    <li><a href="../views/solicitud_prestamos.php">Solicitar Préstamo</a></li>
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <li><a href="../includes/logout.php" class="btn-logout">Cerrar sesión</a></li>
                    <?php else: ?>
                        <li><a href="login.php" class="btn-login">Iniciar sesión</a></li>
                        <li><a href="register.php" class="btn-register">Registrarse</a></li>
                    <?php endif; ?>
                </ul>
            </nav>

        </div>
    </header>

    <section id="hero">
        <h2>Bienvenido a FinancieraYA</h2>
        <p>Soluciones financieras diseñadas para vos.</p>
        <a href="../views/solicitud_prestamos.php" class="cta-button">Solicitar Préstamo</a>
    </section>

    <section id="about">
        <h2>Acerca de Nosotros</h2>
        <p>Brindamos servicios financieros accesibles, rápidos y confiables para ayudarte a crecer.</p>
    </section>

    <footer>
        <p>&copy; 2025 FinancieraYA. Todos los derechos reservados.</p>
    </footer>

</body>

</html>