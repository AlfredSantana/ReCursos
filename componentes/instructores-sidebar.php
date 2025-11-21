<?php
// Consulta para instructores destacados
$query_instructores = "SELECT u.nombre, u.avatar, COUNT(c.id) as total_cursos 
                      FROM usuarios u 
                      LEFT JOIN cursos c ON u.id = c.instructor_id 
                      WHERE u.es_instructor = TRUE 
                      GROUP BY u.id 
                      ORDER BY total_cursos DESC 
                      LIMIT 3";
$result_instructores = mysqli_query($conexion, $query_instructores);

while ($instructor = mysqli_fetch_assoc($result_instructores)):
    ?>
    <div class="instructor-sidebar-item">
        <img src="<?php echo htmlspecialchars($instructor['avatar']); ?>"
            alt="<?php echo htmlspecialchars($instructor['nombre']); ?>">
        <div>
            <div class="instructor-sidebar-name"><?php echo htmlspecialchars($instructor['nombre']); ?></div>
            <div class="instructor-sidebar-stats"><?php echo $instructor['total_cursos']; ?> cursos</div>
        </div>
    </div>
<?php endwhile; ?>