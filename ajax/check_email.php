<?php
include '../includes/db.php';

if (isset($_GET['email'])) {
    $email = $_GET['email'];
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    echo json_encode(['existe' => $stmt->num_rows > 0]);
    $stmt->close();
}
?>
