document.addEventListener("DOMContentLoaded", () => {
    const dniInput = document.getElementById("dni");
    const emailInput = document.getElementById("email");
    const dniError = document.getElementById("dni-error");
    const emailError = document.getElementById("email-error");

    // Función para validar formato DNI
    function validarDNI(dni) {
        return /^\d{7,8}$/.test(dni);
    }

    // Función para validar formato Email
    function validarEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    // Verificar DNI con AJAX
    dniInput.addEventListener("blur", () => {
        const dni = dniInput.value.trim();

        if (!validarDNI(dni)) {
            dniError.textContent = "DNI inválido";
            dniError.style.display = "block";
            return;
        }

        fetch(`../ajax/check_dni.php?dni=${dni}`)
            .then(response => response.json())
            .then(data => {
                if (data.existe) {
                    dniError.textContent = "Este DNI ya está registrado";
                    dniError.style.display = "block";
                    dniError.classList.add("text-danger");
                } else {
                    dniError.style.display = "none";
                }
            })
            .catch(err => console.error(err));
    });

    // Verificar Email con AJAX
    emailInput.addEventListener("blur", () => {
        const email = emailInput.value.trim();

        if (!validarEmail(email)) {
            emailError.textContent = "Este correo ya está registrado";
            emailError.style.display = "block";
            emailError.classList.add("text-danger");
            return;
        }

        fetch(`../ajax/check_email.php?email=${email}`)
            .then(response => response.json())
            .then(data => {
                if (data.existe) {
                    emailError.textContent = "Este correo ya está registrado";
                    emailError.style.display = "block";
                } else {
                    emailError.style.display = "none";
                }
            })
            .catch(err => console.error(err));
    });
});
