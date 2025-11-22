<?php
// componentes/sidebar-cursos-destacados.php
// Consulta para cursos destacados
$query_destacados = "SELECT c.*, u.nombre as instructor_nombre 
                    FROM cursos c 
                    JOIN usuarios u ON c.instructor_id = u.id 
                    WHERE c.estado = 'activo' 
                    ORDER BY c.fecha_creacion DESC 
                    LIMIT 3";
$result_destacados = mysqli_query($conexion, $query_destacados);
?>

<div class="sidebar-section">
    <div class="sidebar-section-header">
        <h3>
            <img src="assets/icons/star.svg" alt="Estrella" class="header-icon">
            Cursos Destacados
        </h3>
    </div>

    <div class="sidebar-content-list">
        <?php if (mysqli_num_rows($result_destacados) > 0): ?>
            <?php while ($curso = mysqli_fetch_assoc($result_destacados)): ?>
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
                                <?php echo ($curso['precio'] == 0) ? 'Gratis' : 'RD$ ' . number_format($curso['precio'], 2, '.', ','); ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <p>No hay cursos destacados</p>
            </div>
        <?php endif; ?>
    </div>
</div>