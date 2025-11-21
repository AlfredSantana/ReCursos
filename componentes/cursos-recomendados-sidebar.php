<?php
// Consulta para cursos recomendados
$query_sidebar_cursos = "SELECT * FROM cursos ORDER BY RAND() LIMIT 3";
$result_sidebar = mysqli_query($conexion, $query_sidebar_cursos);

while ($curso = mysqli_fetch_assoc($result_sidebar)):
    ?>
    <div class="course-sidebar-item">
        <img src="<?php echo htmlspecialchars($curso['imagen']); ?>"
            alt="<?php echo htmlspecialchars($curso['titulo']); ?>">
        <div class="course-sidebar-info">
            <div class="course-sidebar-title"><?php echo htmlspecialchars($curso['titulo']); ?></div>
            <div class="course-sidebar-meta"><?php echo $curso['instructor_nombre']; ?></div>
        </div>
    </div>
<?php endwhile; ?>