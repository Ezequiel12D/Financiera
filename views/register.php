<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../css/style_register.css">

    <title>Registro de Usuario</title>
</head>

<body>

    <section class="h-100">

        <div class="container py-5">

            <div class="card">

                <div class="card-body">

                    <h2 class="text-center mb-4">
                        Registro de Usuario
                    </h2>

                    <div class="btn-container mb-4">

                        <a href="home.php" class="btn btn-secondary">
                            Volver al Home
                        </a>

                        <a href="login.php" class="btn btn-primary">
                            Ya tengo cuenta
                        </a>

                    </div>

                    <form id="register-form" action="../includes/guardar_usuario.php" method="post">

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label" for="nombre">
                                    Nombre/s:
                                </label>

                                <input type="text" name="nombre" id="nombre" class="form-control" required>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label" for="apellido">
                                    Apellido/s:
                                </label>

                                <input type="text" name="apellido" id="apellido" class="form-control" required>

                            </div>

                        </div>

                        <div class="form-group mb-3">

                            <label for="dni">
                                DNI:
                            </label>

                            <input type="text" name="dni" id="dni" class="form-control" maxlength="8"
                                inputmode="numeric" required>

                            <small id="dni-error" class="text-danger" style="display: none;"></small>

                        </div>

                        <div class="mb-3">

                            <label class="form-label" for="telefono">
                                Teléfono:
                            </label>

                            <input type="text" name="telefono" id="telefono" class="form-control" inputmode="tel"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label" for="provincia">
                                Provincia:
                            </label>

                            <select name="provincia" id="provincia" class="form-control" required>

                                <option value="">
                                    Elegir provincia
                                </option>

                                <option value="Buenos Aires">
                                    Buenos Aires
                                </option>

                                <option value="Córdoba">
                                    Córdoba
                                </option>

                                <option value="Santa Fe">
                                    Santa Fe
                                </option>

                            </select>

                        </div>

                        <div class="mb-3">

                            <label class="form-label" for="fechaNacimiento">
                                Fecha de nacimiento:
                            </label>

                            <input type="date" name="fechaNacimiento" id="fechaNacimiento" class="form-control"
                                required>

                        </div>

                        <div class="form-group mb-3">

                            <label for="email">
                                Correo electrónico:
                            </label>

                            <input type="email" name="email" id="email" class="form-control" autocomplete="email"
                                required>

                            <small id="email-error" class="text-danger" style="display: none;"></small>

                        </div>

                        <div class="mb-3">

                            <label class="form-label" for="contrasena">
                                Contraseña:
                            </label>

                            <input type="password" name="contrasena" id="contrasena" class="form-control" minlength="6"
                                autocomplete="new-password" required>

                        </div>

                        <button type="submit" class="btn btn-warning btn-lg mt-3">
                            Registrarse
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </section>

    <script src="../js/validar_dni_email.js"></script>

</body>

</html>