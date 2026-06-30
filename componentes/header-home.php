<?php
// componentes/header-home.php

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Obtener información actualizada del usuario con avatar
include dirname(__DIR__) . "/conexion.php";

$user_id = $_SESSION['user_id'];
$query_user = "SELECT avatar FROM usuarios WHERE id = ?";
$stmt_user = mysqli_prepare($conexion, $query_user);
mysqli_stmt_bind_param($stmt_user, "i", $user_id);
mysqli_stmt_execute($stmt_user);
$result_user = mysqli_stmt_get_result($stmt_user);
$user_data = mysqli_fetch_assoc($result_user);

$user_name = $_SESSION['user_name'] ?? 'Usuario';
$user_type = $_SESSION['user_type'] ?? 'normal';
$user_avatar = $user_data['avatar'] ?? 'assets/usuarios/user-default.avif';

// Actualizar la sesión con el avatar actual
$_SESSION['user_avatar'] = $user_avatar;
?>

<header class="site-header">
    <div class="wrap">
        <!-- Grupo izquierdo: hamburguesa + logo -->
        <div class="header-left">
            <button class="menu-toggle" id="menu-toggle">
                <img src="assets/icons/menu.svg" class="menu-icon" alt="Menú">
            </button>

            <a href="home.php" class="logo-wrap">
                <img src="assets/logo/logo3sf.png" class="logo" alt="ReCursos">
                <div class="logo-text">
                    <span class="logo-re">Re</span><span class="logo-cursos">Cursos</span>
                </div>
            </a>
        </div>

        <!-- Barra de búsqueda en el centro -->
        <div class="search-bar">
            <form method="GET" action="buscar.php" class="search-form">
                <input type="text" name="q" placeholder="Buscar cursos..." class="search-input">
                <button type="submit" class="search-btn">
                    <img src="assets/icons/search.svg" class="search-icon" alt="Buscar">
                </button>
            </form>
        </div>

        <!-- Grupo derecho: theme + user menu -->
        <div class="header-right">
            <button class="theme-btn" id="theme-toggle">
                <img id="theme-icon" src="assets/icons/moon.svg" alt="modo oscuro">
            </button>

            <div class="user-menu">
                <div class="user-dropdown">
                    <img src="<?php echo htmlspecialchars($user_avatar); ?>" class="user-avatar" alt="Perfil">
                    <span><?php echo htmlspecialchars($user_name); ?></span>
                    <?php if ($user_type === 'premium'): ?>
                        <span class="verified-badge">
                            <img src="assets/icons/verified.svg" class="verified-icon" alt="Verificado"
                                title="Usuario Premium Verificado">
                        </span>
                    <?php endif; ?>
                    <div class="dropdown-content">
                        <a href="perfil.php">
                            <img src="assets/icons/user.svg" class="dropdown-icon" alt="Perfil">
                            Mi Perfil
                        </a>
                        <a href="mis-cursos.php">
                            <img src="assets/icons/course.svg" class="dropdown-icon" alt="Cursos">
                            Mis Cursos Creados
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
            </div>
        </div>
    </div>
</header>



<!-- MENÚ LATERAL - FUERA DEL HEADER -->
<div class="side-menu-overlay" id="side-menu-overlay"></div>
<nav class="side-menu" id="side-menu">
    <div class="side-menu-header">
        <button class="close-menu" id="close-menu">
            <img src="assets/icons/close-menu.svg" class="close-icon" alt="Cerrar menú">
        </button>
        <h3>Menú</h3>
    </div>
    <ul>
        <li><a href="home.php">
                <img src="assets/icons/home.svg" class="dropdown-icon" alt="Inicio">Inicio
            </a></li>
        <li><a href="cursos.php">
                <img src="assets/icons/courses.svg" class="dropdown-icon" alt="Cursos">Cursos
            </a></li>
        <li>
            <a href="instructores.php">
                <img src="assets/icons/instructor.svg" class="dropdown-icon" alt="Instructores">
                Instructores
            </a>
        </li>
        <li><a href="suscripcion.php">
                <img src="assets/icons/diamond-premium.svg" class="dropdown-icon" alt="Premium">
                Premium
            </a></li>
        <li><a href="explorar.php">
                <img src="assets/icons/search.svg" class="dropdown-icon" alt="Explorar">
                Explorar
            </a></li>
        <li><a href="mis-cursos.php">
                <img src="assets/icons/course.svg" class="dropdown-icon" alt="Cursos">
                Mis Cursos
            </a></li>
        <li><a href="perfil.php">
                <img src="assets/icons/user.svg" class="dropdown-icon" alt="Perfil">
                Mi Perfil
            </a></li>
        <li><a href="certificados.php">
                <img src="assets/icons/certificate-badge.svg" class="dropdown-icon" alt="Certificados">
                Mis Certificados
            </a></li>
        <li><a href="editar-perfil.php">
                <img src="assets/icons/settings.svg" class="dropdown-icon" alt="Editar Perfil">
                Configuración
            </a></li>
        <li><a href="logout.php" class="logout-btn">
                <img src="assets/icons/logout.svg" class="dropdown-icon" alt="Cerrar Sesión">
                Cerrar Sesión
            </a></li>
    </ul>
</nav>

<!-- ... dentro del header-home.php, después del header ... -->

<!-- Overlay para cerrar dropdown -->
<div class="dropdown-overlay" id="dropdown-overlay"></div>

<!-- MENÚ LATERAL - FUERA DEL HEADER -->
<div class="side-menu-overlay" id="side-menu-overlay"></div>
<nav class="side-menu" id="side-menu">
    <!-- ... tu código del side menu ... -->
</nav>