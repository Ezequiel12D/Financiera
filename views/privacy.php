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
    <title>Política de Privacidad - FinancieraYA</title>
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

    <section id="privacy" class="section-container">
        <h2>Política de Privacidad</h2>
        <p>En FinancieraYA nos comprometemos a proteger tus datos personales y a utilizarlos únicamente para los fines
            previstos en nuestros servicios.</p>

        <h3>Información que recopilamos</h3>
        <ul>
            <li>Datos de contacto (nombre, correo, teléfono)</li>
            <li>Información financiera y de préstamos</li>
            <li>Historial de uso de nuestros servicios</li>
        </ul>

        <h3>Uso de la información</h3>
        <p>Los datos recopilados se utilizan para procesar solicitudes de préstamos, mejorar nuestros servicios y
            cumplir con obligaciones legales.</p>

        <h3>Protección de datos</h3>
        <p>Implementamos medidas de seguridad para proteger tu información personal frente a accesos no autorizados,
            alteraciones o divulgación.</p>

        <h3>Contacto</h3>
        <p>Para consultas sobre privacidad, escribí a <strong>contacto@financieraya.com</strong>.</p>
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