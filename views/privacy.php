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

    <?php include '../includes/header.php'; ?>

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

    <?php include '../includes/footer.php'; ?>

</body>

</html>