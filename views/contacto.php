<?php
session_start();

/* ===============================
   Procesar envío de formulario
================================ */
$mensaje_exitoso = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validar y sanitizar datos
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $mensaje = htmlspecialchars(trim($_POST['mensaje']));

    if (!empty($nombre) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($mensaje)) {
        // Aquí podés enviar email o guardar en BD
        // mail($destino, $asunto, $mensaje, $headers);

        $mensaje_exitoso = "¡Gracias! Tu mensaje ha sido enviado correctamente.";
    } else {
        $error = "Por favor completa todos los campos correctamente.";
    }
}

$pagina_actual = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - FinancieraYA</title>
    <link rel="stylesheet" href="../css/banco.css">
    <link rel="stylesheet" href="../css/styles_home.css">
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .contact-info p {
            margin: 5px 0;
        }

        .contact-form input,
        .contact-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .contact-form label {
            font-weight: bold;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .alert-success {
            color: green;
            margin-bottom: 15px;
        }

        .alert-error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
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
                    <li><a href="solicitud_prestamos.php">Solicitar Préstamo</a></li>

                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <li><a href="historial_prestamos.php" class="btn btn-info">Mis préstamos</a></li>
                        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                            <li><a href="admin_solicitudes.php" class="btn btn-danger">Panel Admin</a></li>
                        <?php endif; ?>
                        <li><a href="../includes/logout.php" class="btn btn-warning">Cerrar sesión</a></li>
                    <?php else: ?>
                        <li><a href="login.php" class="btn btn-primary">Iniciar sesión</a></li>
                        <li><a href="register.php" class="btn btn-success">Registrarse</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <section id="contacto" class="container">
        <h2>Contacto</h2>
        <p>Si tenés dudas o consultas, podés escribirnos o llamarnos.</p>

        <div class="contact-info">
            <p><strong>Teléfono:</strong> +54 9 11 1234-5678</p>
            <p><strong>Email:</strong> contacto@financieraya.com</p>
            <p><strong>Dirección:</strong> Av. Financiera 123, Ciudad, Argentina</p>
        </div>

        <h3>Formulario de contacto</h3>

        <?php if ($mensaje_exitoso): ?>
            <div class="alert-success"><?= $mensaje_exitoso ?></div>
        <?php elseif ($error): ?>
            <div class="alert-error"><?= $error ?></div>
        <?php endif; ?>

        <form action="" method="post" class="contact-form">

            <label for="nombre">Nombre completo:</label>
            <input type="text" id="nombre" name="nombre" placeholder="Nombre completo" required>

            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" placeholder="Correo electrónico" required>

            <label for="mensaje">Mensaje:</label>
            <textarea id="mensaje" name="mensaje" placeholder="Escribe tu mensaje..." required></textarea>

            <button type="submit" class="btn">Enviar mensaje</button>
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