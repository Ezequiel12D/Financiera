<?php
session_start();
include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $email = $_POST['email'];
  $contrasena = $_POST['contrasena'];

  $stmt = $conn->prepare("SELECT id, contrasena, rol FROM usuarios WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->bind_result($usuario_id, $hashed_password, $rol);
  $stmt->fetch();
  $stmt->close();

  if ($usuario_id && password_verify($contrasena, $hashed_password)) {
    $_SESSION['usuario_id'] = $usuario_id;
    $_SESSION['rol'] = $rol;
    header("Location: home.php");
    exit();
  } else {
    echo '<script>alert("Credenciales incorrectas."); window.location.href = "login.php";</script>';
    exit();
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
  <style>
    body {
      background: linear-gradient(135deg, #3498db, #1f78c1);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Arial', sans-serif;
    }

    .card {
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
      overflow: hidden;
    }

    .card-body {
      padding: 2rem;
    }

    h2 {
      text-align: center;
      margin-bottom: 1.5rem;
      color: #0a3d62;
    }

    .form-label {
      font-weight: bold;
      margin-bottom: 0.3rem;
    }

    .form-control {
      border-radius: 8px;
      padding: 10px;
    }

    .btn-primary {
      background-color: #0a3d62;
      border: none;
      width: 100%;
      font-weight: bold;
      padding: 10px;
      border-radius: 8px;
      transition: all 0.3s;
    }

    .btn-primary:hover {
      background-color: #094074;
    }

    .btn-secondary,
    .btn-success {
      border-radius: 8px;
    }

    .btn-container {
      display: flex;
      justify-content: space-between;
      margin-bottom: 1rem;
    }

    .alert {
      margin-top: 1rem;
    }

    @media (max-width: 768px) {
      .btn-container {
        flex-direction: column;
        gap: 10px;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card">
          <div class="card-body">

            <h2>Iniciar Sesión</h2>

            <div class="btn-container mb-4">
              <a href="home.php" class="btn btn-secondary">Volver al Home</a>
              <a href="register.php" class="btn btn-success">Registrarse</a>
            </div>

            <form method="post" action="login.php">
              <div class="form-outline mb-3">
                <label class="form-label" for="dni">DNI:</label>
                <input type="text" id="dni" name="dni" class="form-control" placeholder="Ingrese aquí" required />
              </div>

              <div class="form-outline mb-3">
                <label class="form-label" for="email">E-mail:</label>
                <input type="text" id="email" name="email" class="form-control" placeholder="Ingrese aquí" required />
              </div>

              <div class="form-outline mb-3">
                <label class="form-label" for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" class="form-control" placeholder="Ingrese aquí"
                  required />
              </div>

              <button type="submit" class="btn btn-primary mb-3">Iniciar Sesión</button>
            </form>

            <?php if (!empty($errors)): ?>
              <div class="alert alert-danger" role="alert">
                <?php foreach ($errors as $error): ?>
                  <p><?= $error ?></p>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>

          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>