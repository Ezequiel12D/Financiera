<?php
include '../includes/db.php';

if (isset($_GET['dni'])) {
    $dni = $_GET['dni'];

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE dni = ?");
    $stmt->bind_param("s", $dni);
    $stmt->execute();
    $stmt->store_result();

    echo json_encode(['existe' => $stmt->num_rows > 0]);

    $stmt->close();
}
?>
