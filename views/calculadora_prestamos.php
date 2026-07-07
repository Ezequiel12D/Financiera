<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora de Préstamos</title>
    <link rel="stylesheet" href="../css/banco.css">
    <link rel="stylesheet" href="../css/style_calculadora.css">
    <style>
        /* Modal mejorado */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 400px;
            text-align: center;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 24px;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }

        .btn {
            padding: 10px 20px;
            margin-top: 10px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        input[type=number] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        label {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <header class="topbar">
        <h1>Calculadora de Préstamos</h1>
    </header>

    <section id="loan-calculator" class="container">
        <h2>Calculadora de Préstamos</h2>
        <form id="loan-form" action="#" method="post" onsubmit="return false;">
            <label for="loan-amount">Monto del Préstamo:</label>
            <input type="number" id="loan-amount" name="loan-amount" required min="0.01" step="0.01"
                placeholder="Ej: 10000">

            <label for="interest-rate">Tasa de Interés (%):</label>
            <input type="number" id="interest-rate" name="interest-rate" required min="0" step="0.01"
                placeholder="Ej: 12">

            <label for="loan-term">Plazo del Préstamo (meses):</label>
            <input type="number" id="loan-term" name="loan-term" required min="1" step="1" placeholder="Ej: 12">

            <input type="button" class="btn" value="Calcular" onclick="calculateLoan()">
        </form>

        <!-- Botón Volver al Home -->
        <div style="margin-top: 20px; text-align:center;">
            <a href="home.php" class="btn">Volver al Home</a>
        </div>

        <!-- Modal de resultados -->
        <div id="result-modal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <div id="result"></div>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 FinancieraYA. Todos los derechos reservados.</p>
    </footer>

    <script>
        // Funciones JS
        function calculateLoan() {
            const amount = parseFloat(document.getElementById('loan-amount').value);
            const rate = parseFloat(document.getElementById('interest-rate').value);
            const term = parseInt(document.getElementById('loan-term').value);

            if (isNaN(amount) || isNaN(rate) || isNaN(term) || amount <= 0 || rate < 0 || term <= 0) {
                alert("Por favor ingrese valores válidos.");
                return;
            }

            // Fórmula de cuota mensual
            const monthlyRate = rate / 100 / 12;
            const monthlyPayment = (amount * monthlyRate) / (1 - Math.pow(1 + monthlyRate, -term));

            const totalPayment = monthlyPayment * term;

            document.getElementById('result').innerHTML = `
        <p><strong>Cuota mensual:</strong> $${monthlyPayment.toFixed(2)}</p>
        <p><strong>Total a pagar:</strong> $${totalPayment.toFixed(2)}</p>
    `;
            document.getElementById('result-modal').style.display = "block";
        }

        function closeModal() {
            document.getElementById('result-modal').style.display = "none";
        }

        // Cerrar modal al hacer clic fuera
        window.onclick = function (event) {
            const modal = document.getElementById('result-modal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

</body>

</html>