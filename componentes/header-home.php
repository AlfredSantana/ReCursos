<?php
// componentes/header-home.php


// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Obtener información del usuario para mostrar
$user_name = $_SESSION['user_name'] ?? 'Usuario';
$user_type = $_SESSION['user_type'] ?? 'normal';
?>
<header class="site-header">
    <div class="wrap">
        <a href="home.php" class="logo-wrap">
            <img src="assets/logo/logo3sf.png" class="logo" alt="ReCursos">
            <div class="logo-text">
                <span class="logo-re">Re</span><span class="logo-cursos">Cursos</span>
            </div>
        </a>

        <div class="search-bar">
            <input type="text" placeholder="Buscar cursos...">
            <img src="assets/icons/search.svg" class="search-icon" alt="Buscar">
        </div>

        <nav class="nav">
            <ul>
                <li><a href="home.php">Inicio</a></li>
                <li></li>

                <li><a href="explorar.php">Explorar</a></li>
                <li>
                    <button class="theme-btn" id="theme-toggle">
                        <img id="theme-icon" src="assets/icons/moon.svg" alt="modo oscuro">
                    </button>
                </li>
                <li class="user-menu">
                    <div class="user-dropdown">
                        <img src="<?php echo $_SESSION['user_avatar'] ?? 'assets/usuarios/user-default.avif'; ?>"
                            class="user-avatar" alt="Perfil">
                        <span><?php echo htmlspecialchars($user_name); ?></span>
                        <?php if ($user_type === 'premium'): ?>
                            <span class="premium-badge">
                                <img src="assets/icons/diamond-premium.svg" class="dropdown-icon" alt="Premium">
                            </span>
                        <?php endif; ?>
                        <div class="dropdown-content">
                            <a href="perfil.php">
                                <img src="assets/icons/user.svg" class="dropdown-icon" alt="Perfil">
                                Mi Perfil
                            </a>
                            <a href="mis-cursos.php">
                                <img src="assets/icons/course.svg" class="dropdown-icon" alt="Cursos">
                                Mis Cursos
                            </a>
                            <a href="certificados.php">
                                <img src="assets/icons/certificate-badge.svg" class="dropdown-icon" alt="Certificados">
                                Mis Certificados
                            </a>
                            <?php if ($user_type === 'normal'): ?>
                                <a href="suscripcion.php" class="premium-link">
                                    <img src="assets/icons/diamond-premium.svg" class="dropdown-icon" alt="Premium">
                                    Actualizar a Premium
                                </a>
                            <?php else: ?>
                                <a href="suscripcion.php">
                                    <img src="assets/icons/premium.svg" class="dropdown-icon" alt="Premium">
                                    Gestionar Premium
                                </a>
                            <?php endif; ?>
                            <div class="dropdown-divider"></div>
                            <a href="logout.php" class="logout-btn">
                                <img src="assets/icons/logout.svg" class="dropdown-icon" alt="Cerrar Sesión">
                                Cerrar Sesión
                            </a>
                        </div>
                    </div>
                </li>

            </ul>
        </nav>
    </div>
</header>