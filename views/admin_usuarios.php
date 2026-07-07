<?php

session_start();

require_once __DIR__ . '/../includes/db.php';

/* Verificar inicio de sesión */

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$usuarioActualId = intval($_SESSION['usuario_id']);

/* Verificar rol directamente en la base */

$stmt = $conn->prepare("
    SELECT rol
    FROM usuarios
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param(
    'i',
    $usuarioActualId
);

$stmt->execute();
$stmt->bind_result($rolActual);

if (!$stmt->fetch() || $rolActual !== 'admin') {
    $stmt->close();

    header('Location: home.php');
    exit();
}

$stmt->close();

$_SESSION['rol'] = $rolActual;

/* Crear token CSRF */

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(
        random_bytes(32)
    );
}

/* Paginación */

$limite = 20;

$pagina = isset($_GET['page'])
    ? max(1, intval($_GET['page']))
    : 1;

$offset = ($pagina - 1) * $limite;

/* Contar usuarios */

$resultadoTotal = $conn->query("
    SELECT COUNT(*) AS total
    FROM usuarios
");

$totalUsuarios = intval(
    $resultadoTotal->fetch_assoc()['total']
);

$totalPaginas = max(
    1,
    (int) ceil($totalUsuarios / $limite)
);

if ($pagina > $totalPaginas) {
    $pagina = $totalPaginas;
    $offset = ($pagina - 1) * $limite;
}

/* Obtener usuarios */

$stmt = $conn->prepare("
    SELECT
        id,
        nombre,
        apellido,
        email,
        rol,
        activo
    FROM usuarios
    ORDER BY nombre, apellido
    LIMIT ? OFFSET ?
");

$stmt->bind_param(
    'ii',
    $limite,
    $offset
);

$stmt->execute();
$resultadoUsuarios = $stmt->get_result();

$mensaje = $_GET['mensaje'] ?? '';
$error = $_GET['error'] ?? '';

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Administrar usuarios</title>

    <link rel="stylesheet" href="../css/bootstrap.css">

    <style>
        body {
            background-color: #f4f6f9;
        }

        .contenedor-principal {
            max-width: 1200px;
            margin: 40px auto;
            padding: 25px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.10);
        }

        .acciones {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .paginacion {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 20px;
        }

        .paginacion a {
            padding: 8px 13px;
            background-color: #0d6efd;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .paginacion a:hover {
            background-color: #0b5ed7;
        }

        .paginacion a.activa {
            background-color: #084298;
            font-weight: bold;
        }

        .paginacion a.deshabilitada {
            pointer-events: none;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .tabla-contenedor {
                overflow-x: auto;
            }
        }
    </style>

</head>

<body>

    <main class="contenedor-principal">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h2 class="mb-0">
                Administración de usuarios
            </h2>

            <a href="home.php" class="btn btn-secondary">
                Volver al Home
            </a>

        </div>

        <?php if ($mensaje !== ''): ?>

            <div class="alert alert-success">
                <?= htmlspecialchars($mensaje) ?>
            </div>

        <?php endif; ?>

        <?php if ($error !== ''): ?>

            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>

        <div class="tabla-contenedor">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-dark">

                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Correo electrónico</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>

                </thead>

                <tbody>

                    <?php if ($resultadoUsuarios->num_rows > 0): ?>

                        <?php while ($usuario = $resultadoUsuarios->fetch_assoc()): ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars($usuario['id']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        trim(
                                            $usuario['nombre'] .
                                            ' ' .
                                            $usuario['apellido']
                                        )
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($usuario['email']) ?>
                                </td>

                                <td>

                                    <?php if ($usuario['rol'] === 'admin'): ?>

                                        <span class="badge bg-primary">
                                            Administrador
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-secondary">
                                            Usuario
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <?php if (intval($usuario['activo']) === 1): ?>

                                        <span class="badge bg-success">
                                            Activo
                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-danger">
                                            Bloqueado
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <?php if (intval($usuario['id']) === $usuarioActualId): ?>

                                        <span class="text-muted">
                                            Tu cuenta
                                        </span>

                                    <?php else: ?>

                                        <div class="acciones">

                                            <form action="../includes/cambiar_rol.php" method="post">

                                                <input type="hidden" name="id" value="<?= intval($usuario['id']) ?>">

                                                <input type="hidden" name="rol" value="<?= $usuario['rol'] === 'admin'
                                                    ? 'usuario'
                                                    : 'admin' ?>">

                                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(
                                                    $_SESSION['csrf_token']
                                                ) ?>">

                                                <?php if ($usuario['rol'] === 'admin'): ?>

                                                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                                                        Hacer usuario
                                                    </button>

                                                <?php else: ?>

                                                    <button type="submit" class="btn btn-warning btn-sm">
                                                        Hacer admin
                                                    </button>

                                                <?php endif; ?>

                                            </form>

                                            <form action="../includes/cambiar_estado_usuario.php" method="post">

                                                <input type="hidden" name="id" value="<?= intval($usuario['id']) ?>">

                                                <input type="hidden" name="activo" value="<?= intval($usuario['activo']) === 1
                                                    ? 0
                                                    : 1 ?>">

                                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(
                                                    $_SESSION['csrf_token']
                                                ) ?>">

                                                <?php if (intval($usuario['activo']) === 1): ?>

                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        Bloquear
                                                    </button>

                                                <?php else: ?>

                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        Activar
                                                    </button>

                                                <?php endif; ?>

                                            </form>

                                        </div>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="6" class="text-center">
                                No hay usuarios registrados.
                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

        <?php if ($totalPaginas > 1): ?>

            <nav class="paginacion">

                <a href="?page=<?= max(1, $pagina - 1) ?>" class="<?= $pagina === 1
                         ? 'deshabilitada'
                         : '' ?>">
                    &laquo; Anterior
                </a>

                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>

                    <a href="?page=<?= $i ?>" class="<?= $i === $pagina
                          ? 'activa'
                          : '' ?>">
                        <?= $i ?>
                    </a>

                <?php endfor; ?>

                <a href="?page=<?= min(
                    $totalPaginas,
                    $pagina + 1
                ) ?>" class="<?= $pagina === $totalPaginas
                     ? 'deshabilitada'
                     : '' ?>">
                    Siguiente &raquo;
                </a>

            </nav>

        <?php endif; ?>

    </main>

</body>

</html>

<?php

$stmt->close();
$conn->close();

?>