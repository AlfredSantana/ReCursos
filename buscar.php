<?php
session_start();
include "conexion.php";

// Definir user_id_actual
$user_id_actual = $_SESSION['user_id'] ?? 0;

$query = $_GET['q'] ?? '';
$resultados = [];
$usuarios_resultados = [];
$categoria_filtro = $_GET['categoria'] ?? '';
$categoria_filtro_usuario = $_GET['categoria_usuario'] ?? '';
$tipo_filtro = $_GET['tipo'] ?? 'todos'; // 'todos', 'cursos', 'usuarios'

if (!empty($query)) {
    $search_query = "%$query%";
    
    // BUSCAR CURSOS
    if ($tipo_filtro === 'todos' || $tipo_filtro === 'cursos') {
        $sql_cursos = "SELECT * FROM cursos 
                WHERE (titulo LIKE ? OR descripcion LIKE ? OR categoria LIKE ? OR instructor_nombre LIKE ?)";
        
        $params_cursos = [$search_query, $search_query, $search_query, $search_query];
        
        if (!empty($categoria_filtro)) {
            $sql_cursos .= " AND categoria = ?";
            $params_cursos[] = $categoria_filtro;
        }
        
        $sql_cursos .= " ORDER BY fecha_creacion DESC";
        
        $stmt_cursos = mysqli_prepare($conexion, $sql_cursos);
        mysqli_stmt_bind_param($stmt_cursos, str_repeat('s', count($params_cursos)), ...$params_cursos);
        mysqli_stmt_execute($stmt_cursos);
        $resultados = mysqli_stmt_get_result($stmt_cursos);
    }
    
    // BUSCAR USUARIOS
    if ($tipo_filtro === 'todos' || $tipo_filtro === 'usuarios') {
        $sql_usuarios = "SELECT DISTINCT u.id, u.nombre, u.correo, u.avatar, u.bio, u.pais, u.ciudad, u.tipo_usuario, u.es_instructor, u.fecha_registro 
                         FROM usuarios u
                         LEFT JOIN usuario_categorias uc ON u.id = uc.usuario_id
                         WHERE (u.nombre LIKE ? OR u.correo LIKE ? OR u.bio LIKE ? OR uc.categoria LIKE ?)
                         AND u.id != ?";
                         
        $params_usuarios = [$search_query, $search_query, $search_query, $search_query, $user_id_actual];
        
        // Si hay filtro por categoría de usuario
        if (!empty($categoria_filtro_usuario)) {
            $sql_usuarios .= " AND uc.categoria = ?";
            $params_usuarios[] = $categoria_filtro_usuario;
        }
        
        $stmt_usuarios = mysqli_prepare($conexion, $sql_usuarios);
        mysqli_stmt_bind_param($stmt_usuarios, str_repeat('s', count($params_usuarios)), ...$params_usuarios);
        mysqli_stmt_execute($stmt_usuarios);
        $usuarios_resultados = mysqli_stmt_get_result($stmt_usuarios);
    }
}

// Obtener todas las categorías para el filtro
$categorias_query = "SELECT DISTINCT categoria FROM cursos ORDER BY categoria";
$categorias_result = mysqli_query($conexion, $categorias_query);
$categorias = [];
while ($categoria = mysqli_fetch_assoc($categorias_result)) {
    $categorias[] = $categoria['categoria'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Buscar — ReCursos</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="responsive.css">
    <script defer src="js/theme.js"></script>
</head>
<body>
    <?php include "componentes/header-home.php"; ?>

    <div class="page-wrap">
        <main class="main-content">
            <section class="container">
                <div class="search-header">
                    <h1>Resultados de búsqueda</h1>
                    
                    <?php if (!empty($query)): ?>
                        <p>Buscando: "<strong><?php echo htmlspecialchars($query); ?></strong>"</p>
                    <?php else: ?>
                        <p>Encuentra cursos y usuarios</p>
                    <?php endif; ?>
                </div>

                <!-- Filtros de búsqueda mejorados -->
<div class="search-filters">
    <form method="GET" action="buscar.php" class="filters-form">
        <input type="hidden" name="q" value="<?php echo htmlspecialchars($query); ?>">
        
        <div class="filter-group">
            <label for="tipo">Buscar en:</label>
            <select id="tipo" name="tipo" onchange="this.form.submit()">
                <option value="todos" <?php echo ($tipo_filtro === 'todos') ? 'selected' : ''; ?>>Cursos y Usuarios</option>
                <option value="cursos" <?php echo ($tipo_filtro === 'cursos') ? 'selected' : ''; ?>>Solo Cursos</option>
                <option value="usuarios" <?php echo ($tipo_filtro === 'usuarios') ? 'selected' : ''; ?>>Solo Usuarios</option>
            </select>
        </div>
        
        <?php if ($tipo_filtro === 'todos' || $tipo_filtro === 'cursos'): ?>
        <div class="filter-group">
            <label for="categoria">Filtrar categoría cursos:</label>
            <select id="categoria" name="categoria" onchange="this.form.submit()">
                <option value="">Todas las categorías</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?php echo htmlspecialchars($categoria); ?>" 
                        <?php echo ($categoria_filtro === $categoria) ? 'selected' : ''; ?>>
                        <?php echo ucfirst(htmlspecialchars($categoria)); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>
        
        <?php if ($tipo_filtro === 'todos' || $tipo_filtro === 'usuarios'): ?>
        <div class="filter-group">
            <label for="categoria_usuario">Filtrar especialidad usuarios:</label>
            <select id="categoria_usuario" name="categoria_usuario" onchange="this.form.submit()">
                <option value="">Todas las especialidades</option>
                <option value="programacion" <?php echo ($categoria_filtro_usuario === 'programacion') ? 'selected' : ''; ?>>Programación</option>
                <option value="diseno" <?php echo ($categoria_filtro_usuario === 'diseno') ? 'selected' : ''; ?>>Diseño</option>
                <option value="marketing" <?php echo ($categoria_filtro_usuario === 'marketing') ? 'selected' : ''; ?>>Marketing</option>
                <option value="musica" <?php echo ($categoria_filtro_usuario === 'musica') ? 'selected' : ''; ?>>Música</option>
                <option value="fotografia" <?php echo ($categoria_filtro_usuario === 'fotografia') ? 'selected' : ''; ?>>Fotografía</option>
                <option value="negocios" <?php echo ($categoria_filtro_usuario === 'negocios') ? 'selected' : ''; ?>>Negocios</option>
                <option value="idiomas" <?php echo ($categoria_filtro_usuario === 'idiomas') ? 'selected' : ''; ?>>Idiomas</option>
                <option value="otros" <?php echo ($categoria_filtro_usuario === 'otros') ? 'selected' : ''; ?>>Otros</option>
            </select>
        </div>
        <?php endif; ?>
    </form>
</div>

                <?php if (!empty($query)): ?>
                    <!-- Resultados de Usuarios -->
                    <?php if (($tipo_filtro === 'todos' || $tipo_filtro === 'usuarios') && mysqli_num_rows($usuarios_resultados) > 0): ?>
                        <div class="search-results-section">
                            <h2 class="results-section-title">Usuarios Encontrados</h2>
                            <div class="usuarios-grid">
                                <?php while ($usuario = mysqli_fetch_assoc($usuarios_resultados)): ?>
                                    <article class="card-usuario">
                                        <div class="usuario-avatar">
                                            <img src="<?php echo htmlspecialchars($usuario['avatar']); ?>" 
                                                 alt="<?php echo htmlspecialchars($usuario['nombre']); ?>">
                                        </div>
                                        <div class="usuario-info">
                                            <h3>
                                                <?php echo htmlspecialchars($usuario['nombre']); ?>
                                                <?php if ($usuario['tipo_usuario'] === 'premium'): ?>
                                                    <span class="verified-badge-small" title="Usuario Premium">
                                                        <img src="assets/icons/verified.svg" alt="Premium">
                                                    </span>
                                                <?php endif; ?>
                                            </h3>
                                            <p class="usuario-email"><?php echo htmlspecialchars($usuario['correo']); ?></p>
                                            
                                            <?php if (!empty($usuario['bio'])): ?>
                                                <p class="usuario-bio"><?php echo htmlspecialchars(substr($usuario['bio'], 0, 100)); ?><?php echo strlen($usuario['bio']) > 100 ? '...' : ''; ?></p>
                                            <?php endif; ?>
                                            
                                            <div class="usuario-badges">
                                                <?php if ($usuario['es_instructor']): ?>
                                                    <span class="badge-instructor-small">Instructor</span>
                                                <?php endif; ?>
                                                <span class="badge-member-small">Miembro desde <?php echo date('M Y', strtotime($usuario['fecha_registro'])); ?></span>
                                            </div>
                                            
                                            <a href="perfil-terceros.php?id=<?php echo $usuario['id']; ?>" class="btn btn-outline btn-sm">
    Ver Perfil
</a>
                                        </div>
                                    </article>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Resultados de Cursos -->
                    <?php if (($tipo_filtro === 'todos' || $tipo_filtro === 'cursos') && mysqli_num_rows($resultados) > 0): ?>
                        <div class="search-results-section">
                            <h2 class="results-section-title">Cursos Encontrados</h2>
                            <div class="results-count">
                                <p>Se encontraron <strong><?php echo mysqli_num_rows($resultados); ?></strong> curso(s)</p>
                            </div>
                            
                            <div class="cursos-grid">
                                <?php while ($curso = mysqli_fetch_assoc($resultados)): ?>
                                    <article class="card-curso">
                                        <img src="<?php echo htmlspecialchars($curso['imagen']); ?>" 
                                             alt="<?php echo htmlspecialchars($curso['titulo']); ?>" loading="lazy">
                                        <div class="card-body">
                                            <h3><?php echo htmlspecialchars($curso['titulo']); ?></h3>
                                            <p class="meta">
                                                Por <a href="perfil-terceros.php?id=<?php echo $curso['instructor_id']; ?>" class="instructor-link">
    <?php echo htmlspecialchars($curso['instructor_nombre']); ?>
</a> · 
                                                <?php echo $curso['duracion_horas']; ?> horas · 
                                                <span class="categoria-badge"><?php echo ucfirst($curso['categoria']); ?></span>
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
                        </div>
                    <?php endif; ?>

                    <!-- Mensaje si no hay resultados -->
                    <?php if ((($tipo_filtro === 'todos' || $tipo_filtro === 'cursos') && mysqli_num_rows($resultados) === 0) && 
                          (($tipo_filtro === 'todos' || $tipo_filtro === 'usuarios') && mysqli_num_rows($usuarios_resultados) === 0)): ?>
                        <div class="empty-state">
                            <img src="assets/icons/search-empty.svg" alt="Sin resultados">
                            <h3>No se encontraron resultados</h3>
                            <p>Intenta con otros términos de búsqueda o <a href="explorar.php">explora todos los cursos</a>.</p>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <!-- Estado inicial de búsqueda -->
                    <div class="empty-state">
                        <img src="assets/icons/search.svg" alt="Buscar">
                        <h3>Ingresa un término de búsqueda</h3>
                        <p>Encuentra cursos y usuarios sobre programación, diseño, marketing y más.</p>
                        
                        <div class="popular-categories">
                            <h4>Términos populares:</h4>
                            <div class="categories-grid">
                                <a href="buscar.php?q=programacion" class="category-tag">Programación</a>
                                <a href="buscar.php?q=diseno" class="category-tag">Diseño</a>
                                <a href="buscar.php?q=marketing" class="category-tag">Marketing</a>
                                <a href="buscar.php?q=musica" class="category-tag">Música</a>
                                <a href="buscar.php?q=javascript" class="category-tag">JavaScript</a>
                                <a href="buscar.php?q=python" class="category-tag">Python</a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <?php include "componentes/footer.php"; ?>

    <script>
        // JavaScript para hacer la búsqueda más interactiva
        document.addEventListener('DOMContentLoaded', function() {
            // Focus en el campo de búsqueda si está vacío
            const urlParams = new URLSearchParams(window.location.search);
            const searchQuery = urlParams.get('q');
            
            if (!searchQuery) {
                // Podrías agregar un campo de búsqueda aquí si quieres
            }
            
            // Agregar evento a los tags de categorías
            document.querySelectorAll('.category-tag').forEach(tag => {
                tag.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.location.href = this.href;
                });
            });
        });
    </script>
</body>

</html>