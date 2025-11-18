<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_name = $_SESSION['user_name'] ?? 'Usuario';
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
                        <span class="stat-number">5</span>
                        <span class="stat-label">Logros obtenidos</span>
                    </div>
                </div>

                <div class="hero-user-actions">
                    <a class="btn btn-primary" href="mis-cursos.php">Continuar aprendiendo</a>
                    <a class="btn btn-ghost" href="explorar.php">Explorar cursos</a>
                </div>
            </div>

            <div class="hero-user-image">
                <img src="assets/banners/banner1.png" alt="Aprendizaje continuo">
                <div class="hero-user-overlay"></div>
            </div>
        </section>

        <!---------------------------------------------->
        <!-- CURSOS EN PROGRESO -->
        <!---------------------------------------------->
        <section class="container">
            <h2 class="container-titles">Tus cursos en progreso</h2>

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

        <!-- Incluir secciones comunes -->
        <?php
        // Si no tienes secciones-comunes.php, puedes copiar las secciones del index.php aquí
        include "componentes/secciones-comunes.php";
        ?>

    </div>

    <?php include "componentes/footer.php"; ?>
</body>

</html>