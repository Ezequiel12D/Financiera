<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style_calculadora.css">
    <title>Calculadora de Préstamos</title>
</head>

<body>
    <header>
        <h1>Calculadora de Préstamos</h1>
    </header>

    <section id="loan-calculator">
        <h2>Calculadora de Préstamos</h2>
        <form id="loan-form" action="loan_calculator.php" method="post">
            <label for="loan-amount">Monto del Préstamo:</label>
            <input type="text" id="loan-amount" name="loan-amount" required>

            <label for="interest-rate">Tasa de Interés (%):</label>
            <input type="text" id="interest-rate" name="interest-rate" required>

            <label for="loan-term">Plazo del Préstamo (en meses):</label>
            <input type="text" id="loan-term" name="loan-term" required>

            <input type="button" value="Calcular" onclick="calculateLoan()">
        </form>

        <div id="result-modal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <div id="result"></div>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2023 Financiera XYZ. Todos los derechos reservados.</p>
    </footer>

    <script src="../js/calculadora.js"></script>
</body>

</html>