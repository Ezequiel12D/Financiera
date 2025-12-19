<?php
session_start();
include 'db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../views/home.php");
    exit();
}

$id = intval($_GET['id']);
$rol = $_GET['rol'];

$stmt = $conn->prepare("UPDATE usuarios SET rol = ? WHERE id = ?");
$stmt->bind_param("si", $rol, $id);
$stmt->execute();

header("Location: ../views/admin_usuarios.php");
