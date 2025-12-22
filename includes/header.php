<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pagina_actual = basename($_SERVER['PHP_SELF']);
?>

<header class="main-header">
    <div class="header-container">

        <h1 class="logo">FinancieraYA</h1>

        <nav>
            <ul class="nav-links">

                <!-- INICIO -->
                <?php if ($pagina_actual !== 'home.php'): ?>
                    <li>
                        <a href="home.php">Inicio</a>
                    </li>
                <?php endif; ?>

                <!-- SOLICITAR PRÉSTAMO -->
                <li>
                    <a href="../views/solicitud_prestamos.php">
                        Solicitar Préstamo
                    </a>
                </li>

                <?php if (isset($_SESSION['usuario_id'])): ?>

                    <!-- PERFIL -->
                    <?php if ($pagina_actual !== 'perfil.php'): ?>
                        <li>
                            <a href="perfil.php" class="btn btn-home">
                                Perfil
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- MIS PRÉSTAMOS -->
                    <li>
                        <a href="historial_prestamos.php">
                            Mis préstamos
                        </a>
                    </li>

                    <!-- ADMIN -->
                    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                        <li>
                            <a href="admin_solicitudes.php">
                                Panel Admin
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- LOGOUT -->
                    <li>
                        <a href="../includes/logout.php" class="btn-logout">
                            Cerrar sesión
                        </a>
                    </li>

                <?php else: ?>

                    <li>
                        <a href="login.php">Iniciar sesión</a>
                    </li>
                    <li>
                        <a href="register.php">Registrarse</a>
                    </li>

                <?php endif; ?>

            </ul>
        </nav>

    </div>
</header>