document.addEventListener('DOMContentLoaded', function() {
    const dniInput = document.getElementById('dni');
    const errorMsg = document.getElementById('dni-error');

    dniInput.addEventListener('input', function() {
        const dni = this.value.trim();

        if (dni.length === 0) {
            errorMsg.style.display = 'none';
            return;
        }

        fetch(`../includes/verificar_dni.php?dni=${encodeURIComponent(dni)}`)
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    errorMsg.style.display = 'block';
                } else {
                    errorMsg.style.display = 'none';
                }
            })
            .catch(err => {
                console.error('Error al verificar DNI:', err);
            });
    });
});
