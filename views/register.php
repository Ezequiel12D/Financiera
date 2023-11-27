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
                <form action="../includes/guardar_usuario.php" method="post" class="card card-registration">
                    <div class="card-body">
                        <h3 class="text-uppercase">Registro de Usuario</h3>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="form-outline">
                                    <label class="form-label" for="form3Example1m">Nombre/s:</label>
                                    <input type="text" id="form3Example1m" class="form-control form-control-lg" />
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="form-outline">
                                    <label class="form-label" for="form3Example1n">Apellido/s:</label>
                                    <input type="text" id="form3Example1n" class="form-control form-control-lg" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="form3Example8" placeholder="hola">DNI:</label>
                            <input type="text" id="form3Example8" class="form-control-lg" />
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="form3Example99">Teléfono:</label>
                            <input type="text" id="form3Example100" class="form-control-lg" />
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="provincia">Provincia:</label>
                            <select id="provincia" class="select-control">
                                <option value="">Elegir provincia</option>
                                <option value="Buenos Aires">Buenos Aires</option>
                                <option value="Catamarca">Catamarca</option>
                                <option value="Chaco">Chaco</option>
                                <option value="Chubut">Chubut</option>
                                <option value="Córdoba">Córdoba</option>
                                <option value="Corrientes">Corrientes</option>
                                <option value="Entre Ríos">Entre Ríos</option>
                                <option value="Formosa">Formosa</option>
                                <option value="Jujuy">Jujuy</option>
                                <option value="La Pampa">La Pampa</option>
                                <option value="La Rioja">La Rioja</option>
                                <option value="Mendoza">Mendoza</option>
                                <option value="Misiones">Misiones</option>
                                <option value="Neuquén">Neuquén</option>
                                <option value="Río Negro">Río Negro</option>
                                <option value="Salta">Salta</option>
                                <option value="San Juan">San Juan</option>
                                <option value="San Luis">San Luis</option>
                                <option value="Santa Cruz">Santa Cruz</option>
                                <option value="Santa Fe">Santa Fe</option>
                                <option value="Santiago del Estero">Santiago del Estero</option>
                                <option value="Tierra del Fuego">Tierra del Fuego</option>
                                <option value="Tucumán">Tucumán</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="fechaNacimiento">Fecha de Nacimiento:</label>
                            <input type="date" id="fechaNacimiento" name="fechaNacimiento" class="form-control-lg">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="form3Example99">Correo electrónico:</label>
                            <input type="text" id="form3Example99" class="form-control-lg" />
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="form3Example9">Contraseña:</label>
                            <div class="input-group">
                                <input type="password" name="contrasena" id="form3Example9" class="form-control-lg">
                                <button id="togglePassword" class="btn btn-outline-secondary" type="button">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                    </button>
                            </div>
                            <span id="lengthValue"></span>
                            <button type="submit" class="btn btn-warning btn-lg ms-2">Registrarse</button>
                        </div>
                </form>
            </div>
        </section>
</body>

</html>