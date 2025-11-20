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

// Obtener cursos creados por el usuario
$query_cursos = "SELECT * FROM cursos WHERE instructor_id = ? ORDER BY fecha_creacion DESC";
$stmt_cursos = mysqli_prepare($conexion, $query_cursos);
mysqli_stmt_bind_param($stmt_cursos, "i", $user_id);
mysqli_stmt_execute($stmt_cursos);
$cursos_result = mysqli_stmt_get_result($stmt_cursos);

// Contar estadísticas
$total_cursos = mysqli_num_rows($cursos_result);
$cursos_gratis = 0;
$cursos_premium = 0;
$total_ingresos = 0;

while ($curso = mysqli_fetch_assoc($cursos_result)) {
    if ($curso['precio'] == 0) {
        $cursos_gratis++;
    } else {
        $cursos_premium++;
        $total_ingresos += $curso['precio'];
    }
}

// Volver al inicio del resultado para usarlo después
mysqli_data_seek($cursos_result, 0);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Mis Cursos — ReCursos</title>
    <link rel="stylesheet" href="estilos.css">
    <script defer src="js/theme.js"></script>
</head>

<body>

    <?php include "componentes/header-home.php"; ?>

    <div class="page-wrap">
        <main class="main-content">
            <!-- Header de Mis Cursos -->
            <section class="profile-header">
                <div class="container">
                    <div class="profile-info">
                        <div class="profile-details">
                            <h1>Mis Cursos Creados</h1>
                            <p>Gestiona todos los cursos que has creado en ReCursos</p>

                            <div class="profile-stats">
                                <div class="profile-stat">
                                    <strong><?php echo $total_cursos; ?></strong>
                                    <span>Total de cursos</span>
                                </div>
                                <div class="profile-stat">
                                    <strong><?php echo $cursos_gratis; ?></strong>
                                    <span>Cursos gratis</span>
                                </div>
                                <div class="profile-stat">
                                    <strong><?php echo $cursos_premium; ?></strong>
                                    <span>Cursos premium</span>
                                </div>
                                <div class="profile-stat">
                                    <strong>RD$ <?php echo $total_ingresos; ?></strong>
                                    <span>Ingresos potenciales</span>
                                </div>
                            </div>
                        </div>

                        <div class="profile-actions">
                            <a href="crear-curso.php" class="btn btn-primary">Crear Nuevo Curso</a>
                            <a href="perfil.php" class="btn btn-outline">Volver al Perfil</a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Lista de Cursos -->
            <section class="container">
                <div class="section-header">
                    <h2>Tus Cursos (<?php echo $total_cursos; ?>)</h2>
                    <div class="view-options">
                        <a href="crear-curso.php" class="btn btn-primary">+ Nuevo Curso</a>
                    </div>
                </div>

                <?php if ($total_cursos > 0): ?>
                    <div class="cursos-grid">
                        <?php while ($curso = mysqli_fetch_assoc($cursos_result)): ?>
                            <article class="card-curso">
                                <img src="<?php echo htmlspecialchars($curso['imagen']); ?>"
                                    alt="<?php echo htmlspecialchars($curso['titulo']); ?>" loading="lazy">
                                <div class="card-body">
                                    <h3><?php echo htmlspecialchars($curso['titulo']); ?></h3>
                                    <p class="meta">
                                        <?php echo $curso['duracion_horas']; ?> horas ·
                                        <?php echo ucfirst($curso['nivel']); ?> ·
                                        <span class="categoria-badge"><?php echo ucfirst($curso['categoria']); ?></span>
                                    </p>
                                    <p class="desc"><?php echo htmlspecialchars($curso['descripcion']); ?></p>
                                    <div class="card-foot">
                                        <span class="price <?php echo ($curso['precio'] > 0) ? 'premium' : 'free'; ?>">
                                            <?php echo ($curso['precio'] == 0) ? 'Gratis' : 'RD$ ' . $curso['precio']; ?>
                                        </span>
                                        <div class="card-actions">
                                            <a href="editar-curso.php?id=<?php echo $curso['id']; ?>" class="link">Editar</a>
                                            <a href="curso.php?id=<?php echo $curso['id']; ?>" class="link">Ver</a>
                                            <a href="gestionar-curso.php?id=<?php echo $curso['id']; ?>"
                                                class="link">Gestionar</a>
                                        </div>
                                    </div>
                                    <div class="card-meta">
                                        <small>Creado: <?php echo date('d/m/Y', strtotime($curso['fecha_creacion'])); ?></small>
                                        <?php if (isset($curso['estado'])): ?>
                                            <span class="estado-badge estado-<?php echo $curso['estado']; ?>">
                                                <?php echo ucfirst($curso['estado']); ?>
                                            </span>
                                        <?php endif; ?>
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
                        <div class="empty-actions">
                            <a href="crear-curso.php" class="btn btn-primary">Crear mi primer curso</a>
                            <a href="explorar.php" class="btn btn-outline">Inspirarme con otros cursos</a>
                        </div>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <?php include "componentes/footer.php"; ?>
</body>

</html>