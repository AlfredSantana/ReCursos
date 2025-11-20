<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include "conexion.php";

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'Usuario';

// Obtener estadísticas del usuario
$query_cursos_creados = "SELECT COUNT(*) as total FROM cursos WHERE instructor_id = ?";
$stmt_creados = mysqli_prepare($conexion, $query_cursos_creados);
mysqli_stmt_bind_param($stmt_creados, "i", $user_id);
mysqli_stmt_execute($stmt_creados);
$result_creados = mysqli_stmt_get_result($stmt_creados);
$cursos_creados = mysqli_fetch_assoc($result_creados)['total'] ?? 0;

// Obtener cursos recientes creados por el usuario
$query_cursos_recientes = "SELECT * FROM cursos WHERE instructor_id = ? ORDER BY fecha_creacion DESC LIMIT 2";
$stmt_recientes = mysqli_prepare($conexion, $query_cursos_recientes);
mysqli_stmt_bind_param($stmt_recientes, "i", $user_id);
mysqli_stmt_execute($stmt_recientes);
$cursos_recientes = mysqli_stmt_get_result($stmt_recientes);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Inicio — ReCursos</title>
    <link rel="stylesheet" href="estilos.css">
    <script defer src="js/theme.js"></script>
</head>

<body>

    <?php include "componentes/header-home.php"; ?>

    <div class="page-wrap">
        <main class="main-content">
            <!---------------------------------------------->
            <!-- HERO PARA USUARIOS LOGUEADOS -->
            <!---------------------------------------------->
            <section class="hero-user">
                <div class="hero-user-content">
                    <h1>¡Bienvenido de vuelta, <?php echo htmlspecialchars($user_name); ?>! 👋</h1>
                    <p>Continúa tu aprendizaje o descubre nuevos cursos que podrían interesarte.</p>

                    <div class="hero-user-stats">
                        <div class="stat">
                            <span class="stat-number">3</span>
                            <span class="stat-label">Cursos en progreso</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number">12</span>
                            <span class="stat-label">Horas aprendidas</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number"><?php echo $cursos_creados; ?></span>
                            <span class="stat-label">Cursos creados</span>
                        </div>
                    </div>

                    <div class="hero-user-actions">
                        <?php if ($cursos_creados > 0): ?>
                            <a class="btn btn-primary" href="mis-cursos.php">Mis Cursos Creados</a>
                        <?php else: ?>
                            <a class="btn btn-primary" href="crear-curso.php">Crear mi primer curso</a>
                        <?php endif; ?>
                        <a class="btn btn-ghost" href="explorar.php">Explorar cursos</a>
                        <a class="btn btn-outline" href="mis-cursos.php">Continuar aprendiendo</a>
                    </div>
                </div>

                <div class="hero-user-image">
                    <img src="assets/banners/banner1.png" alt="Aprendizaje continuo">
                    <div class="hero-user-overlay"></div>
                </div>
            </section>

            <!---------------------------------------------->
            <!-- CURSOS EN PROGRESO (TU CÓDIGO ORIGINAL) -->
            <!---------------------------------------------->
            <section class="container">
                <div class="section-header">
                    <h2 class="container-titles">Tus cursos en progreso</h2>
                    <a href="mis-cursos.php" class="view-all">Ver todos</a>
                </div>

                <div class="cursos-grid">
                    <article class="card-curso">
                        <img src="assets/cursos/curso3.jpg" alt="JavaScript fundamentos" loading="lazy">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 65%"></div>
                        </div>
                        <div class="card-body">
                            <h3>JavaScript Fundamentos</h3>
                            <p class="meta">65% completado · 6.5/10 horas</p>
                            <p class="desc">Variables, funciones, DOM y eventos.</p>
                            <div class="card-foot">
                                <span class="progress-text">Continuar</span>
                                <a class="link" href="curso.php?id=javascript">Ver curso</a>
                            </div>
                        </div>
                    </article>

                    <article class="card-curso">
                        <img src="assets/cursos/guitarra.jpg" alt="Guitarra para principiantes" loading="lazy">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 30%"></div>
                        </div>
                        <div class="card-body">
                            <h3>Guitarra Desde Cero</h3>
                            <p class="meta">30% completado · 1.8/6 horas</p>
                            <p class="desc">Acordes básicos, ritmos y primeras canciones.</p>
                            <div class="card-foot">
                                <span class="progress-text">Continuar</span>
                                <a class="link" href="curso.php?id=guitarra">Ver curso</a>
                            </div>
                        </div>
                    </article>

                    <article class="card-curso">
                        <img src="assets/cursos/fotografia.jpg" alt="Fotografía profesional" loading="lazy">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 20%"></div>
                        </div>
                        <div class="card-body">
                            <h3>Fotografía Profesional</h3>
                            <p class="meta">20% completado · 1.2/6 horas</p>
                            <p class="desc">Uso de cámara, iluminación, composición y edición.</p>
                            <div class="card-foot">
                                <span class="progress-text">Continuar</span>
                                <a class="link" href="curso.php?id=fotografia">Ver curso</a>
                            </div>
                        </div>
                    </article>
                </div>
            </section>

            <!-- Sección de Cursos Recientes Creados -->
            <?php if ($cursos_creados > 0): ?>
                <section class="container">
                    <div class="section-header">
                        <h2>Tus Cursos Recientes</h2>
                        <a href="mis-cursos.php" class="view-all">Ver todos</a>
                    </div>

                    <div class="cursos-grid">
                        <?php while ($curso = mysqli_fetch_assoc($cursos_recientes)): ?>
                            <article class="card-curso">
                                <img src="<?php echo htmlspecialchars($curso['imagen']); ?>"
                                    alt="<?php echo htmlspecialchars($curso['titulo']); ?>" loading="lazy">
                                <div class="card-body">
                                    <h3><?php echo htmlspecialchars($curso['titulo']); ?></h3>
                                    <p class="meta">
                                        <?php echo $curso['duracion_horas']; ?> horas ·
                                        <?php echo ucfirst($curso['nivel']); ?>
                                    </p>
                                    <p class="desc"><?php echo htmlspecialchars($curso['descripcion']); ?></p>
                                    <div class="card-foot">
                                        <span class="price">
                                            <?php echo ($curso['precio'] == 0) ? 'Gratis' : 'RD$ ' . $curso['precio']; ?>
                                        </span>
                                        <div class="card-actions">
                                            <a href="editar-curso.php?id=<?php echo $curso['id']; ?>" class="link">Editar</a>
                                            <a href="curso.php?id=<?php echo $curso['id']; ?>" class="link">Ver</a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                </section>
            <?php else: ?>
                <!-- Si no tiene cursos, mostrar llamada a la acción -->
                <section class="container">
                    <div class="empty-state">
                        <img src="assets/icons/course-empty.svg" alt="Sin cursos">
                        <h3>Comienza a crear cursos</h3>
                        <p>Comparte tu conocimiento con la comunidad de ReCursos creando tu primer curso.</p>
                        <a href="crear-curso.php" class="btn btn-primary">Crear mi primer curso</a>
                    </div>
                </section>
            <?php endif; ?>

            <!-- Resto de las secciones (cursos recomendados, etc.) -->
            <?php include "componentes/secciones-comunes.php"; ?>
        </main>
    </div>

    <?php include "componentes/footer.php"; ?>
</body>

</html>