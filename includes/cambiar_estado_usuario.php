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

/* Verificar el rol directamente en la base */

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

/* Verificar token de seguridad */

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

$activo = isset($_POST['activo'])
    ? intval($_POST['activo'])
    : -1;

/* Validar datos */

if ($id <= 0 || !in_array($activo, [0, 1], true)) {
    volverConMensaje('error', 'Los datos recibidos no son válidos.');
}

/* Evitar que el administrador se bloquee a sí mismo */

if ($id === $adminId) {
    volverConMensaje(
        'error',
        'No podés bloquear tu propia cuenta.'
    );
}

/* Comprobar que el usuario exista */

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

/* Cambiar el estado */

$stmt = $conn->prepare("
    UPDATE usuarios
    SET activo = ?
    WHERE id = ?
");

$stmt->bind_param(
    'ii',
    $activo,
    $id
);

if (!$stmt->execute()) {
    $stmt->close();

    volverConMensaje(
        'error',
        'No se pudo cambiar el estado del usuario.'
    );
}

$stmt->close();
$conn->close();

$mensaje = $activo === 1
    ? 'Usuario activado correctamente.'
    : 'Usuario bloqueado correctamente.';

volverConMensaje('mensaje', $mensaje);