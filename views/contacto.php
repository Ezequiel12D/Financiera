<?php
session_start();
$pagina_actual = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles_home.css">
    <title>Contacto - FinancieraYA</title>
</head>
<body>

<header class="main-header">
    <div class="header-container">
        <h1 class="logo">FinancieraYA</h1>
        <nav>
            <ul class="nav-links">
                <?php if ($pagina_actual !== 'home.php'): ?>
                    <li><a href="home.php">Inicio</a></li>
                <?php endif; ?>
                <li><a href="../views/solicitud_prestamos.php">Solicitar Préstamo</a></li>

                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <li><a href="historial_prestamos.php" class="btn btn-info">Mis préstamos</a></li>
                    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                        <li><a href="admin_solicitudes.php" class="btn btn-danger">Panel Admin</a></li>
                    <?php endif; ?>
                    <li><a href="../includes/logout.php" class="btn-logout">Cerrar sesión</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="btn-login">Iniciar sesión</a></li>
                    <li><a href="register.php" class="btn-register">Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<section id="contacto" class="section-container">
    <h2>Contacto</h2>
    <p>Si tenés dudas o consultas, podés escribirnos o llamarnos.</p>

    <div class="contact-info">
        <p><strong>Teléfono:</strong> +54 9 11 1234-5678</p>
        <p><strong>Email:</strong> contacto@financieraya.com</p>
        <p><strong>Dirección:</strong> Av. Financiera 123, Ciudad, Argentina</p>
    </div>

    <h3>Formulario de contacto</h3>
    <form action="#" method="post" class="contact-form">
        <input type="text" name="nombre" placeholder="Nombre completo" required>
        <input type="email" name="email" placeholder="Correo electrónico" required>
        <textarea name="mensaje" placeholder="Escribe tu mensaje..." required></textarea>
        <button type="submit" class="btn btn-warning">Enviar mensaje</button>
    </form>
</section>

<footer class="main-footer">
    <div class="footer-container">
        <p>&copy; 2025 FinancieraYA. Todos los derechos reservados.</p>
        <div class="footer-links">
            <a href="privacy.php">Política de Privacidad</a> |
            <a href="contacto.php">Contacto</a>
        </div>
    </div>
</footer>

</body>
</html>
