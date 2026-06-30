<?php
// componentes/cursos-recomendados-sidebar.php
// Consulta para cursos recomendados
$query_sidebar_cursos = "SELECT c.*, u.nombre as instructor_nombre 
                        FROM cursos c 
                        JOIN usuarios u ON c.instructor_id = u.id 
                        WHERE c.estado = 'activo' 
                        ORDER BY RAND() LIMIT 3";
$result_sidebar = mysqli_query($conexion, $query_sidebar_cursos);
?>

<div class="sidebar-section">
    <div class="sidebar-section-header">
        <h3>
            <img src="assets/icons/courses.svg" alt="Cursos" class="header-icon">
            Cursos Destacados
        </h3>
    </div>

    <div class="sidebar-content-list">
        <?php if (mysqli_num_rows($result_sidebar) > 0): ?>
            <?php while ($curso = mysqli_fetch_assoc($result_sidebar)): ?>
                <div class="sidebar-item">
                    <div class="item-image">
                        <img src="<?php echo htmlspecialchars($curso['imagen']); ?>"
                            alt="<?php echo htmlspecialchars($curso['titulo']); ?>">
                    </div>
                    <div class="item-info">
                        <h4 class="item-title"><?php echo htmlspecialchars($curso['titulo']); ?></h4>
                        <p class="item-meta"><?php echo htmlspecialchars($curso['instructor_nombre']); ?></p>
                        <div class="item-footer">
                            <span class="item-price">
                                <?php echo ($curso['precio'] == 0) ? 'Gratis' : 'RD$ ' . $curso['precio']; ?>
                            </span>
                            <a href="curso.php?id=<?php echo $curso['id']; ?>" class="item-link">Ver</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <p>No hay cursos recomendados</p>
            </div>
        <?php endif; ?>
    </div>
</div>