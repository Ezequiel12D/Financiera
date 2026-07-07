<?php

session_start();
require_once __DIR__ . '/../includes/db.php';

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $email = trim($_POST['email'] ?? '');
  $contrasena = $_POST['contrasena'] ?? '';

  if ($email === '' || $contrasena === '') {

    $errors[] = 'Todos los campos son obligatorios.';

  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $errors[] = 'Ingresá un correo electrónico válido.';

  } else {

    $stmt = $conn->prepare("
            SELECT id, contrasena, rol, activo
            FROM usuarios
            WHERE email = ?
            LIMIT 1
        ");

    if (!$stmt) {

      $errors[] = 'Error al preparar el inicio de sesión.';

    } else {

      $stmt->bind_param('s', $email);
      $stmt->execute();

      $stmt->bind_result(
        $usuarioId,
        $contrasenaGuardada,
        $rol,
        $activo
      );

      if (
        $stmt->fetch() &&
        intval($activo) === 1 &&
        password_verify($contrasena, $contrasenaGuardada)
      ) {

        session_regenerate_id(true);

        $_SESSION['usuario_id'] = $usuarioId;
        $_SESSION['rol'] = $rol;

        $stmt->close();

        header('Location: home.php');
        exit();

      } else {

        $errors[] = 'Correo electrónico o contraseña incorrectos.';

      }

      $stmt->close();
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

  <link href="../css/bootstrap.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(135deg, #3498db, #1f78c1);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: Arial, sans-serif;
      padding: 20px;
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

    .btn-container .btn {
      flex: 1;
    }

    .alert {
      margin-top: 1rem;
    }

    .alert p {
      margin-bottom: 5px;
    }

    .alert p:last-child {
      margin-bottom: 0;
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

        <a href="home.php" class="btn btn-secondary">
          Volver al Home
        </a>

        <a href="register.php" class="btn btn-success">
          Registrarse
        </a>

      </div>

      <form method="post" action="login.php">

        <div class="form-outline mb-3">

          <label class="form-label" for="email">
            E-mail:
          </label>

          <input type="email" id="email" name="email" class="form-control" placeholder="Ingrese su correo"
            value="<?= htmlspecialchars($email) ?>" autocomplete="email" required>

        </div>

        <div class="form-outline mb-3">

          <label class="form-label" for="contrasena">
            Contraseña:
          </label>

          <input type="password" id="contrasena" name="contrasena" class="form-control"
            placeholder="Ingrese su contraseña" autocomplete="current-password" required>

        </div>

        <button type="submit" class="btn btn-primary mb-3">
          Iniciar Sesión
        </button>

      </form>

      <?php if (!empty($errors)): ?>

        <div class="alert alert-danger" role="alert">

          <?php foreach ($errors as $error): ?>

            <p>
              <?= htmlspecialchars($error) ?>
            </p>

          <?php endforeach; ?>

        </div>

      <?php endif; ?>

    </div>

  </div>

</body>

</html>