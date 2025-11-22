<?php
// componentes/instructores-sidebar.php
// Consulta para instructores destacados
$query_instructores = "SELECT u.id, u.nombre, u.avatar, COUNT(c.id) as total_cursos 
                      FROM usuarios u 
                      LEFT JOIN cursos c ON u.id = c.instructor_id 
                      WHERE u.es_instructor = TRUE 
                      GROUP BY u.id 
                      ORDER BY total_cursos DESC 
                      LIMIT 3";
$result_instructores = mysqli_query($conexion, $query_instructores);
?>

<div class="sidebar-section">
    <div class="sidebar-section-header">
        <h3>
            <img src="assets/icons/instructor.svg" alt="Instructor" class="header-icon">
            Instructores Destacados
        </h3>
    </div>

    <div class="sidebar-content-list">
        <?php if (mysqli_num_rows($result_instructores) > 0): ?>
            <?php while ($instructor = mysqli_fetch_assoc($result_instructores)): ?>
                <div class="sidebar-item">
                    <div class="item-avatar">
                        <img src="<?php echo htmlspecialchars($instructor['avatar']); ?>"
                            alt="<?php echo htmlspecialchars($instructor['nombre']); ?>">
                    </div>
                    <div class="item-info">
                        <h4 class="item-title"><?php echo htmlspecialchars($instructor['nombre']); ?></h4>
                        <p class="item-meta"><?php echo $instructor['total_cursos']; ?> cursos</p>
                        <div class="item-footer">
                            <a href="perfil-publico.php?id=<?php echo $instructor['id']; ?>" class="item-link">
                                Ver perfil
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <p>No hay instructores destacados</p>
            </div>
        <?php endif; ?>
    </div>
</div>