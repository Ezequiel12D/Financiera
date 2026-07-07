<?php
session_start();
include 'db.php';

// Verificar que el usuario esté logueado y sea admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../views/home.php");
    exit();
}

// Validar parámetros GET
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$activo = isset($_GET['activo']) ? intval($_GET['activo']) : -1;

// Validar valores permitidos
if ($id <= 0 || !in_array($activo, [0, 1])) {
    header("Location: ../views/admin_usuarios.php?error=Parámetros inválidos");
    exit();
}

// Evitar que el admin se desactive a sí mismo
if ($id == $_SESSION['usuario_id']) {
    header("Location: ../views/admin_usuarios.php?error=No puedes desactivar tu propio usuario");
    exit();
}

// Ejecutar actualización
$stmt = $conn->prepare("UPDATE usuarios SET activo = ? WHERE id = ?");
if ($stmt) {
    $stmt->bind_param("ii", $activo, $id);
    if ($stmt->execute()) {
        header("Location: ../views/admin_usuarios.php?mensaje=Estado actualizado correctamente");
        exit();
    } else {
        header("Location: ../views/admin_usuarios.php?error=Error al actualizar: " . urlencode($stmt->error));
        exit();
    }
} else {
    header("Location: ../views/admin_usuarios.php?error=Error en la consulta: " . urlencode($conn->error));
    exit();
}
