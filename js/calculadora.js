function calculateLoan() {
    const loanAmount = parseFloat(document.getElementById("loan-amount").value);
    const interestRate = parseFloat(document.getElementById("interest-rate").value);
    const loanTerm = parseInt(document.getElementById("loan-term").value);

    if (isNaN(loanAmount) || isNaN(interestRate) || isNaN(loanTerm)) {
        alert("Por favor, ingresa valores válidos.");
        return;
    }

    const monthlyInterestRate = (interestRate / 100) / 12;
    const monthlyPayment = (loanAmount * monthlyInterestRate) / (1 - Math.pow(1 + monthlyInterestRate, -loanTerm));
    const totalInterest = (monthlyPayment * loanTerm) - loanAmount;

    const resultContent = document.getElementById("result");
    resultContent.innerHTML = `
        <p>El pago mensual estimado es: $${monthlyPayment.toFixed(2)}</p>
        <p>Intereses totales pagados: $${totalInterest.toFixed(2)}</p>
    `;

    const modal = document.getElementById("result-modal");
    modal.style.display = "block";
}

function closeModal() {
    const modal = document.getElementById("result-modal");
    modal.style.display = "none";
}

window.onclick = function (event) {
    const modal = document.getElementById("result-modal");
    if (event.target === modal) {
        modal.style.display = "none";
    }
};
