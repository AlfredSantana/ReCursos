<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include "conexion.php";

$user_id = $_SESSION['user_id'];

// Obtener información actualizada del usuario (solo una vez)
$query = "SELECT *, DATE(fecha_registro) as fecha_union FROM usuarios WHERE id = ?";
$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_data = mysqli_fetch_assoc($result);

// Actualizar variables de sesión con datos actualizados
$_SESSION['user_name'] = $user_data['nombre'];
$_SESSION['user_email'] = $user_data['correo'];
$_SESSION['user_avatar'] = $user_data['avatar'];

$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];
$user_avatar = $_SESSION['user_avatar'];
$user_type = $_SESSION['user_type'] ?? 'normal';
$user_bio = $user_data['bio'] ?? ''; // Obtener la bio

// Obtener cursos creados por el usuario
$query_cursos = "SELECT * FROM cursos WHERE instructor_id = ? ORDER BY fecha_creacion DESC";
$stmt_cursos = mysqli_prepare($conexion, $query_cursos);
mysqli_stmt_bind_param($stmt_cursos, "i", $user_id);
mysqli_stmt_execute($stmt_cursos);
$cursos_result = mysqli_stmt_get_result($stmt_cursos);

// Contar total de cursos creados
$total_creados = mysqli_num_rows($cursos_result);

// Obtener algunos cursos populares para mostrar (simulando "cursos tomados")
$query_cursos_populares = "SELECT * FROM cursos WHERE instructor_id != ? ORDER BY fecha_creacion DESC LIMIT 3";
$stmt_populares = mysqli_prepare($conexion, $query_cursos_populares);
mysqli_stmt_bind_param($stmt_populares, "i", $user_id);
mysqli_stmt_execute($stmt_populares);
$cursos_populares = mysqli_stmt_get_result($stmt_populares);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title>Mi Perfil — ReCursos</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="responsive.css">
    <script defer src="js/theme.js"></script>
    <script defer src="js/menu.js"></script>
</head>

<body>

    <?php include "componentes/header-home.php"; ?>

    <div class="page-wrap">
        <main class="main-content">
            <!-- Header del Perfil -->
            <section class="profile-header">
                <div class="container">
                    <div class="profile-info">
                        <div class="profile-avatar-section">
                            <img src="<?php echo htmlspecialchars($user_avatar); ?>" alt="Avatar" class="profile-avatar"
                                id="profile-avatar">
                            <input type="file" id="avatar-upload" accept="image/*" style="display: none;">
                            <button class="btn-avatar-edit" onclick="document.getElementById('avatar-upload').click()">
                                <img src="assets/icons/camera.svg" alt="Cambiar foto">
                            </button>
                        </div>

                        <div class="profile-details">
                            <h1>
                                <?php echo htmlspecialchars($user_name); ?>
                                <?php if ($user_type === 'premium'): ?>
                                    <span class="verified-badge-profile">
                                        <img src="assets/icons/verified.svg" class="verified-icon-profile" alt="Verificado"
                                            title="Usuario Premium Verificado">
                                    </span>
                                <?php endif; ?>
                            </h1>
                            <p class="profile-email"><?php echo htmlspecialchars($user_email); ?></p>

                            <!-- MOSTRAR BIO AQUÍ -->
                            <?php if (!empty($user_bio)): ?>
                                <div class="profile-bio">
                                    <p><?php echo nl2br(htmlspecialchars($user_bio)); ?></p>
                                </div>
                            <?php else: ?>
                                <div class="profile-bio empty">
                                    <p>¡Aún no tienes una biografía! <a href="editar-perfil.php">Agrega una</a> para que
                                        otros usuarios te conozcan mejor.</p>
                                </div>
                            <?php endif; ?>

                            <div class="profile-badges">
                                <span class="badge-role">Estudiante</span>
                                <?php if ($user_type === 'premium'): ?>
                                    <span class="badge-premium">
                                        <img src="assets/icons/verified.svg" class="badge-premium-icon" alt="Verificado">
                                        Premium
                                    </span>
                                <?php endif; ?>
                                <span class="badge-member">Miembro desde
                                    <?php echo date('M Y', strtotime($user_data['fecha_union'] ?? 'now')); ?></span>
                            </div>

                            <div class="profile-stats">
                                <div class="profile-stat">
                                    <strong>3</strong>
                                    <span>Cursos tomados</span>
                                </div>
                                <div class="profile-stat">
                                    <strong><?php echo $total_creados; ?></strong>
                                    <span>Cursos creados</span>
                                </div>
                                <div class="profile-stat">
                                    <strong>12h</strong>
                                    <span>Horas aprendidas</span>
                                </div>
                            </div>
                        </div>

                        <div class="profile-actions">
                            <a href="editar-perfil.php" class="btn btn-outline">Editar Perfil</a>
                            <a href="crear-curso.php" class="btn btn-primary">Crear Curso</a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Contenido con Pestañas -->
            <section class="container">
                <div class="tabs">
                    <button class="tab-btn active" data-tab="cursos-tomados">Cursos Recomendados</button>
                    <button class="tab-btn" data-tab="cursos-creados">Mis Cursos Creados</button>
                    <button class="tab-btn" data-tab="configuracion">Configuración</button>
                </div>

                <!-- Pestaña de Cursos Recomendados -->
                <div class="tab-content active" id="cursos-tomados">
                    <div class="section-header">
                        <h2>Cursos Recomendados</h2>
                        <a href="explorar.php" class="view-all">Ver todos</a>
                    </div>

                    <?php if (mysqli_num_rows($cursos_populares) > 0): ?>
                        <div class="cursos-grid">
                            <?php while ($curso = mysqli_fetch_assoc($cursos_populares)): ?>
                                <article class="card-curso">
                                    <img src="<?php echo htmlspecialchars($curso['imagen']); ?>"
                                        alt="<?php echo htmlspecialchars($curso['titulo']); ?>" loading="lazy">
                                    <div class="card-body">
                                        <h3><?php echo htmlspecialchars($curso['titulo']); ?></h3>
                                        <p class="meta">Por <?php echo htmlspecialchars($curso['instructor_nombre']); ?> ·
                                            <?php echo $curso['duracion_horas']; ?> horas
                                        </p>
                                        <p class="desc"><?php echo htmlspecialchars($curso['descripcion']); ?></p>
                                        <div class="card-foot">
                                            <span
                                                class="price"><?php echo ($curso['precio'] == 0) ? 'Gratis' : 'RD$ ' . $curso['precio']; ?></span>
                                            <a class="link" href="curso.php?id=<?php echo $curso['id']; ?>">Ver curso</a>
                                        </div>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <img src="assets/icons/course-empty.svg" alt="Sin cursos">
                            <h3>No hay cursos disponibles</h3>
                            <p>Pronto tendremos cursos recomendados para ti.</p>
                            <a href="crear-curso.php" class="btn btn-primary">Crear Primer Curso</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pestaña de Cursos Creados -->
                <div class="tab-content" id="cursos-creados">
                    <div class="section-header">
                        <h2>Mis Cursos Creados</h2>
                        <a href="crear-curso.php" class="btn btn-primary">Crear Nuevo Curso</a>
                    </div>

                    <?php if (mysqli_num_rows($cursos_result) > 0): ?>
                        <div class="cursos-grid">
                            <?php while ($curso = mysqli_fetch_assoc($cursos_result)): ?>
                                <article class="card-curso">
                                    <img src="<?php echo htmlspecialchars($curso['imagen']); ?>"
                                        alt="<?php echo htmlspecialchars($curso['titulo']); ?>" loading="lazy">
                                    <div class="card-body">
                                        <h3><?php echo htmlspecialchars($curso['titulo']); ?></h3>
                                        <p class="meta"><?php echo $curso['duracion_horas']; ?> horas ·
                                            <?php echo ucfirst($curso['nivel']); ?>
                                        </p>
                                        <p class="desc"><?php echo htmlspecialchars($curso['descripcion']); ?></p>
                                        <div class="card-foot">
                                            <span
                                                class="price"><?php echo ($curso['precio'] == 0) ? 'Gratis' : 'RD$ ' . $curso['precio']; ?></span>
                                            <div class="card-actions">
                                                <a href="editar-curso.php?id=<?php echo $curso['id']; ?>"
                                                    class="link">Editar</a>
                                                <a href="curso.php?id=<?php echo $curso['id']; ?>" class="link">Ver</a>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <img src="assets/icons/course-empty.svg" alt="Sin cursos">
                            <h3>Aún no has creado ningún curso</h3>
                            <p>Comparte tu conocimiento creando tu primer curso en ReCursos.</p>
                            <a href="crear-curso.php" class="btn btn-primary">Crear mi primer curso</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pestaña de Configuración -->
                <div class="tab-content" id="configuracion">
                    <div class="empty-state">
                        <img src="assets/icons/settings.svg" alt="Configuración">
                        <h3>Configuración de cuenta</h3>
                        <p>Gestiona la configuración de tu cuenta y preferencias.</p>
                        <a href="editar-perfil.php" class="btn btn-primary">Editar Perfil</a>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <?php include "componentes/footer.php"; ?>

    <script>
        // JavaScript para las pestañas
        document.addEventListener('DOMContentLoaded', function () {
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Remover active de todos
                    tabBtns.forEach(b => b.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));

                    // Agregar active al seleccionado
                    btn.classList.add('active');
                    const tabId = btn.getAttribute('data-tab');
                    const tabContent = document.getElementById(tabId);
                    if (tabContent) {
                        tabContent.classList.add('active');
                    }
                });
            });

            // Upload de avatar (simulado por ahora)
            document.getElementById('avatar-upload').addEventListener('change', function (e) {
                if (this.files && this.files[0]) {
                    // Aquí iría la lógica para subir la imagen al servidor
                    alert('Funcionalidad de subir avatar en desarrollo. Por ahora, esta función es demostrativa.');

                    // Simular cambio de avatar (en un caso real, esto se haría con AJAX)
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById('profile-avatar').src = e.target.result;
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
    </script>
</body>

</html>