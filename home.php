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

// Verificar si es usuario nuevo (primer login)
$es_usuario_nuevo = false;
if (!isset($_SESSION['primer_login_verificado'])) {
    $query_primer_login = "SELECT fecha_registro FROM usuarios WHERE id = ?";
    $stmt_primer_login = mysqli_prepare($conexion, $query_primer_login);
    mysqli_stmt_bind_param($stmt_primer_login, "i", $user_id);
    mysqli_stmt_execute($stmt_primer_login);
    $result_primer_login = mysqli_stmt_get_result($stmt_primer_login);

    if ($usuario = mysqli_fetch_assoc($result_primer_login)) {
        $fecha_registro = new DateTime($usuario['fecha_registro']);
        $hoy = new DateTime();
        $diferencia = $hoy->diff($fecha_registro);

        // Considerar usuario nuevo si se registró hace menos de 7 días
        $es_usuario_nuevo = ($diferencia->days < 7);
    }
    $_SESSION['primer_login_verificado'] = true;
    $_SESSION['es_usuario_nuevo'] = $es_usuario_nuevo;
} else {
    $es_usuario_nuevo = $_SESSION['es_usuario_nuevo'] ?? false;
}

// Obtener estadísticas del usuario
$query_cursos_creados = "SELECT COUNT(*) as total FROM cursos WHERE instructor_id = ?";
$stmt_creados = mysqli_prepare($conexion, $query_cursos_creados);
mysqli_stmt_bind_param($stmt_creados, "i", $user_id);
mysqli_stmt_execute($stmt_creados);
$result_creados = mysqli_stmt_get_result($stmt_creados);
$cursos_creados = mysqli_fetch_assoc($result_creados)['total'] ?? 0;

// Obtener cursos en progreso (estadística real)
$query_cursos_progreso = "SELECT COUNT(*) as total FROM curso_inscripciones WHERE usuario_id = ? AND progreso > 0 AND progreso < 100";
$stmt_progreso = mysqli_prepare($conexion, $query_cursos_progreso);
mysqli_stmt_bind_param($stmt_progreso, "i", $user_id);
mysqli_stmt_execute($stmt_progreso);
$result_progreso = mysqli_stmt_get_result($stmt_progreso);
$cursos_progreso = mysqli_fetch_assoc($result_progreso)['total'] ?? 0;

// Obtener horas aprendidas (estadística real)
$query_horas_aprendidas = "SELECT COALESCE(SUM(c.duracion_horas * (ci.progreso/100)), 0) as horas 
                          FROM curso_inscripciones ci 
                          JOIN cursos c ON ci.curso_id = c.id 
                          WHERE ci.usuario_id = ?";
$stmt_horas = mysqli_prepare($conexion, $query_horas_aprendidas);
mysqli_stmt_bind_param($stmt_horas, "i", $user_id);
mysqli_stmt_execute($stmt_horas);
$result_horas = mysqli_stmt_get_result($stmt_horas);
$horas_aprendidas = round(mysqli_fetch_assoc($result_horas)['horas'] ?? 0, 1);

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
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title>Inicio — ReCursos</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="responsive.css">
    <script defer src="js/theme.js"></script>
    <script defer src="js/menu.js"></script>
</head>

<body>

    <?php include "componentes/header-home.php"; ?>

    <div class="page-wrap">
        <main class="main-content">
            <!---------------------------------------------->
            <!-- HERO MEJORADO PARA USUARIOS LOGUEADOS -->
            <!---------------------------------------------->
            <section class="hero-user <?php echo $es_usuario_nuevo ? 'hero-new-user' : ''; ?>">
                <div class="hero-user-content">
                    <?php if ($es_usuario_nuevo): ?>
                        <!-- HERO PARA USUARIOS NUEVOS -->
                        <div class="welcome-badge">
                            <span class="badge-icon">🎉</span>
                            ¡Nuevo en ReCursos!
                        </div>
                        <h1>¡Bienvenido a ReCursos, <?php echo htmlspecialchars($user_name); ?>! 🚀</h1>
                        <p class="hero-subtitle">Estamos emocionados de tenerte aquí. Comienza tu viaje de aprendizaje o
                            comparte tu conocimiento creando cursos.</p>

                        <div class="hero-user-stats">
                            <div class="stat">
                                <span class="stat-number"><?php echo $cursos_progreso; ?></span>
                                <span class="stat-label">Cursos empezados</span>
                            </div>
                            <div class="stat">
                                <span class="stat-number"><?php echo $horas_aprendidas; ?></span>
                                <span class="stat-label">Horas aprendidas</span>
                            </div>
                            <div class="stat">
                                <span class="stat-number"><?php echo $cursos_creados; ?></span>
                                <span class="stat-label">Cursos creados</span>
                            </div>
                        </div>

                        <div class="hero-user-actions">
                            <?php if ($cursos_creados > 0): ?>
                                <a class="btn btn-primary" href="mis-cursos.php">
                                    <span class="btn-icon">📚</span>
                                    Mis Cursos Creados
                                </a>
                            <?php else: ?>
                                <a class="btn btn-primary" href="crear-curso.php">
                                    <span class="btn-icon">✨</span>
                                    Crear mi primer curso
                                </a>
                            <?php endif; ?>
                            <a class="btn btn-ghost" href="explorar.php">
                                <span class="btn-icon">🔍</span>
                                Explorar cursos
                            </a>
                            <a class="btn btn-outline" href="perfil.php">
                                <span class="btn-icon">👤</span>
                                Completar perfil
                            </a>
                        </div>

                    <?php else: ?>
                        <!-- HERO PARA USUARIOS EXISTENTES -->
                        <h1>¡Bienvenido de vuelta, <?php echo htmlspecialchars($user_name); ?>! 👋</h1>
                        <p class="hero-subtitle">Continúa tu aprendizaje o descubre nuevos cursos que podrían interesarte.
                        </p>

                        <div class="hero-user-stats">
                            <div class="stat">
                                <span class="stat-number"><?php echo $cursos_progreso; ?></span>
                                <span class="stat-label">Cursos en progreso</span>
                            </div>
                            <div class="stat">
                                <span class="stat-number"><?php echo $horas_aprendidas; ?></span>
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
                    <?php endif; ?>
                </div>

                <div class="hero-user-image">
                    <?php if ($es_usuario_nuevo): ?>
                        <img src="assets/banners/banner-welcome.png" alt="Bienvenido a ReCursos">
                    <?php else: ?>
                        <img src="assets/banners/banner-welcome.png" alt="Bienvenido a ReCursos">
                    <?php endif; ?>
                    <div class="hero-user-overlay"></div>
                </div>
            </section>

            <!-- El resto de tu código permanece igual -->
            <!---------------------------------------------->
            <!-- CURSOS EN PROGRESO -->
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

                    <!-- ... resto de los cursos ... -->
                </div>
            </section>

            <!-- ... resto de las secciones ... -->

        </main>
    </div>

    <?php include "componentes/footer.php"; ?>

</body>

</html>