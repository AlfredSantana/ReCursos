<?php
session_start();
include "conexion.php";

// Obtener todos los cursos activos
$query = "SELECT * FROM cursos WHERE estado = 'activo' ORDER BY fecha_creacion DESC";
$cursos_result = mysqli_query($conexion, $query);

// Obtener categorías
$categorias_query = "SELECT DISTINCT categoria FROM cursos WHERE estado = 'activo' ORDER BY categoria";
$categorias_result = mysqli_query($conexion, $categorias_query);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Explorar Cursos — ReCursos</title>
    <link rel="stylesheet" href="estilos.css">
    <script defer src="js/theme.js"></script>
</head>

<body>
    <?php include "componentes/header-home.php"; ?>

    <div class="page-wrap">
        <main class="main-content">
            <section class="container">
                <div class="search-header">
                    <h1>Explorar Todos los Cursos</h1>
                    <p>Descubre todos los cursos disponibles en ReCursos</p>
                </div>

                <?php if (mysqli_num_rows($cursos_result) > 0): ?>
                    <div class="cursos-grid">
                        <?php while ($curso = mysqli_fetch_assoc($cursos_result)): ?>
                            <article class="card-curso">
                                <img src="<?php echo htmlspecialchars($curso['imagen']); ?>"
                                    alt="<?php echo htmlspecialchars($curso['titulo']); ?>">
                                <div class="card-body">
                                    <h3><?php echo htmlspecialchars($curso['titulo']); ?></h3>
                                    <p class="meta">
                                        Por <?php echo htmlspecialchars($curso['instructor_nombre']); ?> ·
                                        <?php echo $curso['duracion_horas']; ?> horas
                                    </p>
                                    <p class="desc"><?php echo htmlspecialchars($curso['descripcion']); ?></p>
                                    <div class="card-foot">
                                        <span class="price">
                                            <?php echo ($curso['precio'] == 0) ? 'Gratis' : 'RD$ ' . $curso['precio']; ?>
                                        </span>
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
                        <p>Pronto tendremos cursos para ti.</p>
                        <a href="crear-curso.php" class="btn btn-primary">Crear Primer Curso</a>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <?php include "componentes/footer.php"; ?>
</body>

</html>