<?php

session_start();

require_once __DIR__ . '/db.php';

function volverConMensaje(string $tipo, string $mensaje): void
{
    header(
        'Location: ../views/admin_usuarios.php?' .
        $tipo . '=' . urlencode($mensaje)
    );
    exit();
}

/* Verificar sesión */

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../views/login.php');
    exit();
}

$adminId = intval($_SESSION['usuario_id']);

/* Verificar que el usuario actual sea administrador */

$stmt = $conn->prepare("
    SELECT rol
    FROM usuarios
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param('i', $adminId);
$stmt->execute();
$stmt->bind_result($rolAdmin);

if (!$stmt->fetch() || $rolAdmin !== 'admin') {
    $stmt->close();

    header('Location: ../views/home.php');
    exit();
}

$stmt->close();

/* Solamente aceptar POST */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    volverConMensaje('error', 'Método no permitido.');
}

/* Validar token CSRF */

$tokenRecibido = $_POST['csrf_token'] ?? '';
$tokenGuardado = $_SESSION['csrf_token'] ?? '';

if (
    $tokenGuardado === '' ||
    !hash_equals($tokenGuardado, $tokenRecibido)
) {
    volverConMensaje(
        'error',
        'La solicitud de seguridad no es válida.'
    );
}

/* Recibir datos */

$id = isset($_POST['id'])
    ? intval($_POST['id'])
    : 0;

$nuevoRol = $_POST['rol'] ?? '';

/* Validar */

if (
    $id <= 0 ||
    !in_array($nuevoRol, ['admin', 'usuario'], true)
) {
    volverConMensaje('error', 'El rol seleccionado no es válido.');
}

/* Evitar que el administrador cambie su propia cuenta */

if ($id === $adminId) {
    volverConMensaje(
        'error',
        'No podés modificar el rol de tu propia cuenta.'
    );
}

/* Comprobar que exista */

$stmt = $conn->prepare("
    SELECT id
    FROM usuarios
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows !== 1) {
    $stmt->close();

    volverConMensaje('error', 'El usuario no existe.');
}

$stmt->close();

/* Actualizar rol */

$stmt = $conn->prepare("
    UPDATE usuarios
    SET rol = ?
    WHERE id = ?
");

$stmt->bind_param(
    'si',
    $nuevoRol,
    $id
);

if (!$stmt->execute()) {
    $stmt->close();

    volverConMensaje(
        'error',
        'No se pudo modificar el rol.'
    );
}

$stmt->close();
$conn->close();

volverConMensaje(
    'mensaje',
    'Rol actualizado correctamente.'
);