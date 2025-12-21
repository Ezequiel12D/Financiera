function validarDNI(dni) {
    return /^\d{7,8}$/.test(dni);
}

document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("dni");

    if (!input) return;

    input.addEventListener("blur", () => {
        if (!validarDNI(input.value)) {
            alert("DNI inválido");
            input.focus();
        }
    });
});
