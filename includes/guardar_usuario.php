<?php

session_start();

require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/register.php');
    exit();
}

$nombre = trim($_POST['nombre'] ?? '');
$apellido = trim($_POST['apellido'] ?? '');
$dni = trim($_POST['dni'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$provincia = trim($_POST['provincia'] ?? '');
$fechaNacimiento = $_POST['fechaNacimiento'] ?? '';
$email = trim($_POST['email'] ?? '');
$contrasena = $_POST['contrasena'] ?? '';

if (
    $nombre === '' ||
    $apellido === '' ||
    $dni === '' ||
    $telefono === '' ||
    $provincia === '' ||
    $fechaNacimiento === '' ||
    $email === '' ||
    $contrasena === ''
) {
    echo "
        <script>
            alert('Todos los campos son obligatorios');
            window.location='../views/register.php';
        </script>
    ";
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "
        <script>
            alert('El correo electrónico no es válido');
            window.location='../views/register.php';
        </script>
    ";
    exit();
}

/* Verificar si el correo ya existe */

$stmt = $conn->prepare("
    SELECT id
    FROM usuarios
    WHERE email = ?
    LIMIT 1
");

$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->close();

    echo "
        <script>
            alert('El correo electrónico ya está registrado');
            window.location='../views/register.php';
        </script>
    ";
    exit();
}

$stmt->close();

/* Verificar si el DNI ya existe */

$stmt = $conn->prepare("
    SELECT id
    FROM usuarios
    WHERE dni = ?
    LIMIT 1
");

$stmt->bind_param('s', $dni);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->close();

    echo "
        <script>
            alert('El DNI ya está registrado');
            window.location='../views/register.php';
        </script>
    ";
    exit();
}

$stmt->close();

/* Encriptar contraseña */

$contrasenaHash = password_hash(
    $contrasena,
    PASSWORD_DEFAULT
);

/* Guardar usuario */

$stmt = $conn->prepare("
    INSERT INTO usuarios
    (
        nombre,
        apellido,
        dni,
        telefono,
        provincia,
        fecha_nacimiento,
        email,
        contrasena,
        saldo,
        rol
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, 'usuario')
");

$stmt->bind_param(
    'ssssssss',
    $nombre,
    $apellido,
    $dni,
    $telefono,
    $provincia,
    $fechaNacimiento,
    $email,
    $contrasenaHash
);

if ($stmt->execute()) {

    $usuarioId = $conn->insert_id;

    session_regenerate_id(true);

    $_SESSION['usuario_id'] = $usuarioId;
    $_SESSION['rol'] = 'usuario';

    $stmt->close();
    $conn->close();

    header('Location: ../views/home.php');
    exit();

} else {

    echo "
        <script>
            alert('Ocurrió un error al registrar el usuario');
            window.location='../views/register.php';
        </script>
    ";
}

$stmt->close();
$conn->close();

?>