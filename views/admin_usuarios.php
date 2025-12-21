<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: home.php");
    exit();
}

$result = $conn->query("
    SELECT id, nombre, apellido, email, rol, activo
    FROM usuarios
    ORDER BY nombre
");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Administrar Usuarios</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
</head>

<body class="container mt-4">

    <h2 class="mb-4"> Administración de Usuarios</h2>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>

            <?php while ($u = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= $u['nombre'] . ' ' . $u['apellido'] ?></td>
                    <td><?= $u['email'] ?></td>
                    <td><?= ucfirst($u['rol']) ?></td>
                    <td>
                        <?= $u['activo'] ?
                            '<span class="badge bg-success">Activo</span>' :
                            '<span class="badge bg-danger">Bloqueado</span>' ?>
                    </td>
                    <td>
                        <?php if ($u['rol'] === 'cliente'): ?>
                            <a href="../includes/cambiar_rol.php?id=<?= $u['id'] ?>&rol=admin" class="btn btn-warning btn-sm">
                                Hacer admin
                            </a>
                        <?php endif; ?>

                        <?php if ($u['activo']): ?>
                            <a href="../includes/cambiar_estado_usuario.php?id=<?= $u['id'] ?>&activo=0"
                                class="btn btn-danger btn-sm">
                                Bloquear
                            </a>
                        <?php else: ?>
                            <a href="../includes/cambiar_estado_usuario.php?id=<?= $u['id'] ?>&activo=1"
                                class="btn btn-success btn-sm">
                                Activar
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>

        </tbody>
    </table>

    <a href="home.php" class="btn btn-secondary mt-3"> Volver</a>

</body>

</html>