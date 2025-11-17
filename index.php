<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>ReCursos — Aprende, enseña y certifícate</title>
    <link rel="stylesheet" href="estilos.css">
    <script defer src="js/theme.js"></script>
</head>

<body>

    <?php include "componentes/header.php"; ?>

    <!---------------------------------------------->
    <!-- HERO -->
    <!---------------------------------------------->
    <section class="hero">
        <div class="hero-content">
            <h1>Aprende nuevas habilidades. Comparte tu conocimiento.</h1>
            <p>Más de 500 cursos en todas las categorías: cocina, música, programación, negocios, fotografía y más.</p>

            <div class="hero-cta">
                <a class="btn btn-primary" href="register.php">Comenzar gratis</a>
                <a class="btn btn-ghost" href="login.php">Iniciar sesión</a>
            </div>

            <p class="hero-premium-note">
                <img src="assets/icons/premium.svg" class="diamond-icon" alt="premium">
                Obtén <strong>ReCursos Premium</strong>: sin anuncios, acceso ilimitado y certificados verificados.
            </p>
        </div>

        <div class="hero-image">
            <img src="assets/banners/hero-placeholder.avif" alt="Estudiantes aprendiendo">
            <div class="hero-overlay"></div>
        </div>
    </section>


    <!---------------------------------------------->
    <!-- CATEGORÍAS -->
    <!---------------------------------------------->
    <section class="container">
        <h2>Categorías más populares</h2>

        <div class="cursos-grid">

            <div class="card-curso">
                <img src="assets/categorias/cocina1.webp" alt="Cocina y Gastronomía">
                <span class="badge">Popular</span>
                <div class="card-body">
                    <h3>Cocina & Gastronomía</h3>
                    <p class="desc">Recetas, técnicas y repostería profesional.</p>
                </div>
            </div>

            <div class="card-curso">
                <img src="assets/categorias/musica.jpg" alt="Música e Instrumentos">
                <span class="badge">Nuevo</span>
                <div class="card-body">
                    <h3>Música e Instrumentos</h3>
                    <p class="desc">Guitarra, piano, canto y teoría musical.</p>
                </div>
            </div>

            <div class="card-curso">
                <img src="assets/categorias/programacion.jpeg" alt="Programación y Desarrollo">
                <span class="badge">Tendencia</span>
                <div class="card-body">
                    <h3>Programación</h3>
                    <p class="desc">Desarrollo web, aplicaciones móviles y más.</p>
                </div>
            </div>

            <div class="card-curso">
                <img src="assets/categorias/baile.jpg" alt="Baile y Fitness">
                <span class="badge">Popular</span>
                <div class="card-body">
                    <h3>Baile & Fitness</h3>
                    <p class="desc">Bachata, merengue, coreografías y salud física.</p>
                </div>
            </div>

        </div>
    </section>


    <!---------------------------------------------->
    <!-- CURSOS DESTACADOS -->
    <!---------------------------------------------->
    <section class="container">
        <h2>Cursos destacados</h2>

        <div class="cursos-grid">

            <article class="card-curso">
                <img src="assets/cursos/cocina1.webp" alt="Cocina Dominicana" loading="lazy">
                <span class="badge">Nuevo</span>
                <div class="card-body">
                    <h3>Cocina Dominicana para Principiantes</h3>
                    <p class="meta">8 horas · Todos los niveles</p>
                    <p class="desc">Aprende platos tradicionales como mangú, sancocho y moro.</p>
                    <div class="card-foot">
                        <span class="price">RD$600</span>
                        <a class="link" href="#">Ver curso</a>
                    </div>
                </div>
            </article>

            <article class="card-curso">
                <img src="assets/cursos/guitarra.jpg" alt="Guitarra para principiantes" loading="lazy">
                <span class="badge">Popular</span>
                <div class="card-body">
                    <h3>Guitarra Desde Cero</h3>
                    <p class="meta">6 horas · Principiante</p>
                    <p class="desc">Acordes básicos, ritmos y primeras canciones.</p>
                    <div class="card-foot">
                        <span class="price">Gratis</span>
                        <a class="link" href="#">Ver curso</a>
                    </div>
                </div>
            </article>

            <article class="card-curso">
                <img src="assets/cursos/curso1.avif" alt="HTML para Principiantes" loading="lazy">
                <span class="badge">Gratis</span>
                <div class="card-body">
                    <h3>HTML para Principiantes</h3>
                    <p class="meta">6 horas · Nivel: Principiante</p>
                    <p class="desc">Aprende a construir páginas web desde cero.</p>
                    <div class="card-foot">
                        <span class="price">Gratis</span>
                        <a class="link" href="#">Ver curso</a>
                    </div>
                </div>
            </article>

            <article class="card-curso">
                <img src="assets/cursos/fotografia.jpg" alt="Fotografía profesional" loading="lazy">
                <span class="badge">Nuevo</span>
                <div class="card-body">
                    <h3>Fotografía Profesional</h3>
                    <p class="meta">6 horas · Intermedio</p>
                    <p class="desc">Uso de cámara, iluminación, composición y edición.</p>
                    <div class="card-foot">
                        <span class="price">RD$1,200</span>
                        <a class="link" href="#">Ver curso</a>
                    </div>
                </div>
            </article>

            <article class="card-curso">
                <img src="assets/cursos/curso2.png" alt="CSS moderno" loading="lazy">
                <span class="badge">Nuevo</span>
                <div class="card-body">
                    <h3>CSS moderno</h3>
                    <p class="meta">8 horas · Intermedio</p>
                    <p class="desc">Flexbox, Grid y diseño responsive.</p>
                    <div class="card-foot">
                        <span class="price">RD$800</span>
                        <a class="link" href="#">Ver curso</a>
                    </div>
                </div>
            </article>

            <article class="card-curso">
                <img src="assets/cursos/curso3.jpg" alt="JavaScript fundamentos" loading="lazy">
                <span class="badge">Popular</span>
                <div class="card-body">
                    <h3>JavaScript Fundamentos</h3>
                    <p class="meta">10 horas · Principiante</p>
                    <p class="desc">Variables, funciones, DOM y eventos.</p>
                    <div class="card-foot">
                        <span class="price">RD$1,200</span>
                        <a class="link" href="#">Ver curso</a>
                    </div>
                </div>
            </article>

            <article class="card-curso">
                <img src="assets/cursos/curso4.jpg" alt="Marketing digital para emprendedores" loading="lazy">
                <span class="badge">Popular</span>
                <div class="card-body">
                    <h3>Marketing Digital</h3>
                    <p class="meta">5 horas · Todos los niveles</p>
                    <p class="desc">Crecimiento en redes, SEO y publicidad.</p>
                    <div class="card-foot">
                        <span class="price">RD$900</span>
                        <a class="link" href="#">Ver curso</a>
                    </div>
                </div>
            </article>

            <article class="card-curso">
                <img src="assets/cursos/curso5.jpg" alt="Introducción a Data Science" loading="lazy">
                <span class="badge">Popular</span>
                <div class="card-body">
                    <h3>Introducción a Data Science</h3>
                    <p class="meta">12 horas · Intermedio</p>
                    <p class="desc">Herramientas de análisis y visualización.</p>
                    <div class="card-foot">
                        <span class="price">RD$1,500</span>
                        <a class="link" href="#">Ver curso</a>
                    </div>
                </div>
            </article>

        </div>
    </section>

    <!---------------------------------------------->
    <!-- INSTRUCTORES DESTACADOS -->
    <!---------------------------------------------->
    <section class="container">
        <h2>Instructores destacados</h2>

        <div class="cursos-grid">

            <div class="card-curso">
                <img src="assets/usuarios/instructor1.jpg" alt="Chef Laura Martínez">
                <span class="badge2">Cocina</span>
                <div class="card-body">
                    <h3>Laura Martínez</h3>
                    <p class="desc">Chef profesional · 15k estudiantes</p>
                    <div class="card-foot">
                        <a class="link" href="#">Ver cursos</a>
                    </div>
                </div>
            </div>

            <div class="card-curso">
                <img src="assets/usuarios/instructor2.jpg" alt="Guitarrista Pedro Santos">
                <span class="badge2">Música</span>
                <div class="card-body">
                    <h3>Pedro Santos</h3>
                    <p class="desc">Instructor de guitarra · 22k estudiantes</p>
                    <div class="card-foot">
                        <a class="link" href="#">Ver cursos</a>
                    </div>
                </div>
            </div>

            <div class="card-curso">
                <img src="assets/usuarios/instructor3.jpg" alt="Mecánico Roberto Cruz">
                <span class="badge2">Mecánica</span>
                <div class="card-body">
                    <h3>Roberto Cruz</h3>
                    <p class="desc">Mecánica automotriz · 18k estudiantes</p>
                    <div class="card-foot">
                        <a class="link" href="#">Ver cursos</a>
                    </div>
                </div>
            </div>

            <div class="card-curso">
                <img src="assets/usuarios/instructor4.jpg" alt="Bailarina Sofía Torres">
                <span class="badge2">Baile</span>
                <div class="card-body">
                    <h3>Sofía Torres</h3>
                    <p class="desc">Baile & Fitness · 25k estudiantes</p>
                    <div class="card-foot">
                        <a class="link" href="#">Ver cursos</a>
                    </div>
                </div>
            </div>

        </div>
    </section>


    <!---------------------------------------------->
    <!-- INSTRUCTOR CALL -->
    <!---------------------------------------------->
    <section class="instructor-call">
        <div class="instructor-call-inner">
            <h2>¿Quieres compartir tus conocimientos?</h2>
            <p>En ReCursos puedes impartir cursos, generar ingresos y llegar a miles de estudiantes alrededor del mundo.
            </p>

            <a href="register.php" class="btn instructor-btn">
                <img src="assets/icons/graduation-hat.svg" class="icon-hat" alt="Instructor">
                Comenzar como instructor
            </a>

        </div>
    </section>

    <!---------------------------------------------->
    <!-- PREMIUM SECTION -->
    <!---------------------------------------------->

    <section class="premium-section">
        <h2>Hazte Premium</h2>
        <p class="premium-sub">Aprende sin límites y sin interrupciones.</p>

        <div class="premium-grid">

            <div class="premium-card">
                <img src="assets/icons/unlock.svg" class="premium-icon">
                <h3>Acceso ilimitado</h3>
                <p>Todos los cursos sin restricciones.</p>
            </div>

            <div class="premium-card">
                <img src="assets/icons/no-ads.svg" class="premium-icon">
                <h3>Sin anuncios</h3>
                <p>Clases sin interrupciones.</p>
            </div>

            <div class="premium-card">
                <img src="assets/icons/certificado.svg" class="premium-icon">
                <h3>Certificados verificados</h3>
                <p>Impulsa tu perfil profesional.</p>
            </div>

            <div class="premium-card">
                <img src="assets/icons/support.svg" class="premium-icon">
                <h3>Soporte prioritario</h3>
                <p>Atención preferencial.</p>
            </div>

        </div>

        <a href="suscripcion.php" class="btn premium-btn">Obtener Premium</a>
    </section>

    <?php include "componentes/footer.php"; ?>


</body>

</html>