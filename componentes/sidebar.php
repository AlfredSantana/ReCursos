<?php
// componentes/sidebar.php
$user_name = $_SESSION['user_name'] ?? 'Usuario';
?>
<aside class="sidebar" id="sidebar">
    <button class="sidebar-close" id="sidebar-close">×</button>

    <div class="sidebar-user">
        <img src="<?php echo $_SESSION['user_avatar'] ?? 'assets/usuarios/user-default.avif'; ?>" alt="Perfil"
            class="sidebar-avatar">
        <div class="sidebar-user-info">
            <h3><?php echo htmlspecialchars($user_name); ?></h3>
            <span class="user-status">Estudiante</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <a href="home.php" class="sidebar-link active">
            <img src="assets/icons/home.svg" alt="Inicio">
            <span>Inicio</span>
        </a>
        <a href="mis-cursos.php" class="sidebar-link">
            <img src="assets/icons/course.svg" alt="Cursos">
            <span>Mis Cursos</span>
        </a>
        <a href="explorar.php" class="sidebar-link">
            <img src="assets/icons/explore.svg" alt="Explorar">
            <span>Explorar Cursos</span>
        </a>
        <a href="crear-curso.php" class="sidebar-link">
            <img src="assets/icons/upload.svg" alt="Crear">
            <span>Crear Curso</span>
        </a>
        <a href="perfil.php" class="sidebar-link">
            <img src="assets/icons/user.svg" alt="Perfil">
            <span>Mi Perfil</span>
        </a>

        <div class="sidebar-divider"></div>

        <a href="suscripcion.php" class="sidebar-link premium">
            <img src="assets/icons/diamond-premium.svg" alt="Premium">
            <span>Obtener Premium</span>
        </a>
    </nav>
</aside>

<!-- OVERLAY PARA MENÚ MÓVIL -->
<div class="sidebar-overlay" id="sidebar-overlay"></div>

<!-- BOTÓN HAMBURGUESA -->
<button class="sidebar-toggle" id="sidebar-toggle">
    <span></span>
    <span></span>
    <span></span>
</button>