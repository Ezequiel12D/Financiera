<?php
session_start();
include '../includes/db.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $dni = trim($_POST['dni']);
  $email = trim($_POST['email']);
  $contrasena = $_POST['contrasena'];

  if (empty($dni) || empty($email) || empty($contrasena)) {
    $errors[] = "Todos los campos son obligatorios.";
  } else {
    $stmt = $conn->prepare("SELECT id, contrasena, rol FROM usuarios WHERE dni = ? AND email = ?");
    $stmt->bind_param("ss", $dni, $email);
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
      $errors[] = "Credenciales incorrectas. Verifica DNI, correo y contraseña.";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión - FinancieraYA</title>
  <link href="../css/bootstrap.css" rel="stylesheet" />
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
      width: 100%;
      max-width: 450px;
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
      gap: 10px;
    }

    .alert {
      margin-top: 1rem;
    }

    @media (max-width: 576px) {
      .btn-container {
        flex-direction: column;
      }
    }
  </style>
</head>

<body>
  <div class="card">
    <div class="card-body">
      <h2>Iniciar Sesión</h2>

      <div class="btn-container">
        <a href="home.php" class="btn btn-secondary">Volver al Home</a>
        <a href="register.php" class="btn btn-success">Registrarse</a>
      </div>

      <form method="post" action="login.php">

        <div class="form-outline mb-3">
          <label class="form-label" for="email">E-mail:</label>
          <input type="email" id="email" name="email" class="form-control" placeholder="Ingrese aquí" required />
        </div>

        <div class="form-outline mb-3">
          <label class="form-label" for="contrasena">Contraseña:</label>
          <input type="password" id="contrasena" name="contrasena" class="form-control" placeholder="Ingrese aquí"
            required />
        </div>

        <button type="submit" class="btn btn-primary mb-3">
          Iniciar Sesión
        </button>

      </form>
      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" role="alert">
          <?php foreach ($errors as $error): ?>
            <p><?= htmlspecialchars($error) ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</body>

</html>