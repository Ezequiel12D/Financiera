<?php
session_start();
include '../includes/db.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $dni = $_POST['dni'];
  $email = $_POST['email'];
  $contrasena = $_POST['contrasena'];

  $stmt = $conn->prepare("SELECT id, contraseña FROM usuarios WHERE email = ?");
  $stmt->bind_param("ss", $dni, $email);
  $stmt->execute();
  $stmt->bind_result($empleado_id, $hashed_password);
  $stmt->fetch();
  $stmt->close();

  if ($empleado_id && password_verify($contrasena, $hashed_password)) {
    $_SESSION['usuario_id'] = $empleado_id;
    header("Location: historial_prestamos.php");
    exit();
  } else {
    $errors[] = "Credenciales incorrectas. Por favor, intenta de nuevo.";
    echo '<script>alert("Usuario no encontrado. Redirigiendo a register.php."); window.location.href = "register.php";</script>';
  }
}
?>

  <!DOCTYPE html>
  <html lang="es">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/bootstrap.css" rel="stylesheet" />
    <title>Iniciar Sesión</title>
  </head>

  <body>
    <section class="">
      <div class="px-4 py-5 px-md-5 text-center text-lg-start" style="background-color: hsl(0, 0%, 96%)">
        <div class="container">
          <div class="row gx-lg-5 align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
              <div class="card">
                <div class="card-body py-5 px-md-5">
                  <form method="post" action="login.php">
                    <div class="row">
                      <div class="form-outline mb-4">
                        <label class="form-label" for="dni">DNI:</label>
                        <input type="text" id="dni" name="dni" class="form-control" placeholder="Ingrese aquí"
                          required />
                      </div>

                      <div class="form-outline mb-4">
                        <label class="form-label" for="email">E-mail:</label>
                        <input type="text" id="email" name="email" class="form-control" placeholder="Ingrese aquí"
                          required />
                      </div>

                      <div class="form-outline mb-4">
                        <label class="form-label" for="contrasena">Contraseña:</label>
                        <input type="password" id="contrasena" name="contrasena" class="form-control"
                          placeholder="Ingrese aquí" required />
                      </div>

                      <button type="submit" class="btn btn-primary btn-block mb-4">
                        Iniciar Sesión
                      </button>
                    </div>
                  </form>
                  <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger" role="alert">
                      <?php foreach ($errors as $error): ?>
                        <p>
                          <?php echo $error; ?>
                        </p>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </body>

  </html>