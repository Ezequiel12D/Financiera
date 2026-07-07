<?php
session_start();
include '../includes/db.php';

/* ===============================
   SEGURIDAD: SOLO ADMIN
================================ */
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

/* Verificar rol directamente en BD */
$stmt = $conn->prepare("SELECT rol FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $_SESSION['usuario_id']);
$stmt->execute();
$stmt->bind_result($rol);
$stmt->fetch();
$stmt->close();

if ($rol !== 'admin') {
    header("Location: home.php");
    exit();
}

/* ===============================
   CSRF TOKEN
================================ */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/* ===============================
   PAGINACIÓN
================================ */
$limit = 20;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

/* Contar total de usuarios */
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM usuarios");
$totalRows = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

/* ===============================
   CONSULTA USUARIOS
================================ */
$sql = "
SELECT id, nombre, apellido, email, rol, activo
FROM usuarios
ORDER BY nombre
LIMIT $limit OFFSET $offset
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Administrar Usuarios</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <style>
        /* Paginación estilo admin panel */
        .pagination {
            margin-top: 20px;
            text-align: center;
            font-family: Arial, sans-serif;
        }

        .pagination a {
            display: inline-block;
            margin: 0 5px;
            padding: 8px 12px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .pagination a:hover {
            background-color: #0056b3;
        }

        .pagination a.active {
            background-color: #0056b3;
            font-weight: bold;
        }

        .pagination a.disabled {
            pointer-events: none;
            opacity: 0.5;
        }
    </style>
</head>

<body class="container mt-4">

    <h2 class="mb-4">Administración de Usuarios</h2>

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
                    <td><?= htmlspecialchars($u['id']) ?></td>
                    <td><?= htmlspecialchars($u['nombre'] . ' ' . $u['apellido']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars(ucfirst($u['rol'])) ?></td>
                    <td>
                        <?= $u['activo'] ?
                            '<span class="badge bg-success">Activo</span>' :
                            '<span class="badge bg-danger">Bloqueado</span>' ?>
                    </td>
                    <td class="d-flex gap-1">
                        <!-- Cambiar rol a admin -->
                        <?php if ($u['rol'] === 'cliente'): ?>
                            <form action="../includes/cambiar_rol.php" method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                <input type="hidden" name="rol" value="admin">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <button type="submit" class="btn btn-warning btn-sm">Hacer admin</button>
                            </form>
                        <?php endif; ?>

                        <!-- Activar/Bloquear usuario -->
                        <form action="../includes/cambiar_estado_usuario.php" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                            <input type="hidden" name="activo" value="<?= $u['activo'] ? 0 : 1 ?>">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <button type="submit" class="btn <?= $u['activo'] ? 'btn-danger' : 'btn-success' ?> btn-sm">
                                <?= $u['activo'] ? 'Bloquear' : 'Activar' ?>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Paginación completa -->
    <div class="pagination">
        <a href="?page=<?= max(1, $page - 1) ?>" class="<?= $page == 1 ? 'disabled' : '' ?>">&laquo; Anterior</a>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
        <a href="?page=<?= min($totalPages, $page + 1) ?>"
            class="<?= $page == $totalPages ? 'disabled' : '' ?>">Siguiente &raquo;</a>
    </div>

    <a href="home.php" class="btn btn-secondary mt-3">Volver</a>

</body>

</html>