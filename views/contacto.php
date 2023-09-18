<section id="contacto">
    <h2>Contacto</h2>
    <p>Estamos aquí para ayudarte. Puedes contactarnos a través del siguiente formulario:</p>
    <form action="contact.php" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        <label for="correo">Correo Electrónico:</label>
        <input type="email" id="correo" name="correo" required>
        <label for="mensaje">Mensaje:</label>
        <textarea id="mensaje" name="mensaje" required></textarea>
        <button type="submit">Enviar</button>
    </form>
</section>