<?php
session_start();
include 'db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../views/home.php");
    exit();
}

$id = intval($_GET['id']);
$activo = intval($_GET['activo']);

$stmt = $conn->prepare("UPDATE usuarios SET activo = ? WHERE id = ?");
$stmt->bind_param("ii", $activo, $id);
$stmt->execute();

header("Location: ../views/admin_usuarios.php");
