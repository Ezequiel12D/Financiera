<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style_register.css">
    <title>Registro de Usuario</title>
</head>

<body>

    <form action="../includes/guardar_usuario.php" method="post">
        <section class="h-100 bg-dark">
            <div class="container py-5">
                <div class="card card-registration">
                    <div class="card-body">
                        <div style="display:flex; justify-content:space-between; margin-bottom:20px;">
                            <a href="home.php" class="btn btn-secondary"
                                style="background:#6c757d; padding:10px 20px; border-radius:5px; color:white; text-decoration:none;">
                                Volver al Home
                            </a>

                            <a href="login.php" class="btn btn-primary"
                                style="background:#0d6efd; padding:10px 20px; border-radius:5px; color:white; text-decoration:none;">
                                Ya tengo cuenta
                            </a>
                        </div>

                        <h3 class="text-uppercase">Registro de Usuario</h3>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label>Nombre/s:</label>
                                <input type="text" name="nombre" class="form-control form-control-lg" required>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label>Apellido/s:</label>
                                <input type="text" name="apellido" class="form-control form-control-lg" required>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label>DNI:</label>
                            <input type="text" name="dni" id="dni" class="form-control-lg" required>
                            <small id="dni-error" style="color:red; display:none;">Este DNI ya está registrado</small>
                        </div>

                        <div class="form-group mb-3">
                            <label>Teléfono:</label>
                            <input type="text" name="telefono" class="form-control-lg" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Provincia:</label>
                            <select name="provincia" class="select-control" required>
                                <option value="">Elegir provincia</option>
                                <option value="Buenos Aires">Buenos Aires</option>
                                <option value="Córdoba">Córdoba</option>
                                <option value="Santa Fe">Santa Fe</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>Fecha de nacimiento:</label>
                            <input type="date" name="fechaNacimiento" class="form-control-lg" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Correo electrónico:</label>
                            <input type="email" name="email" class="form-control-lg" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Contraseña:</label>
                            <input type="password" name="contrasena" class="form-control-lg" required>
                        </div>

                        <button type="submit" class="btn btn-warning btn-lg mt-3">Registrarse</button>

                    </div>
                </div>
            </div>
        </section>
    </form>

    <script src="../js/validar_dni.js"></script>
</body>

</html>