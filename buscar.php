<?php
session_start();
include "conexion.php";

$query = $_GET['q'] ?? '';
$resultados = [];
$categoria_filtro = $_GET['categoria'] ?? '';

if (!empty($query)) {
    $search_query = "%$query%";
    
    // Buscar en título, descripción, categoría e instructor (sin condición de estado)
    $sql = "SELECT * FROM cursos 
            WHERE (titulo LIKE ? OR descripcion LIKE ? OR categoria LIKE ? OR instructor_nombre LIKE ?)";
    
    $params = [$search_query, $search_query, $search_query, $search_query];
    
    // Si hay filtro por categoría, agregarlo
    if (!empty($categoria_filtro)) {
        $sql .= " AND categoria = ?";
        $params[] = $categoria_filtro;
    }
    
    $sql .= " ORDER BY fecha_creacion DESC";
    
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, str_repeat('s', count($params)), ...$params);
    mysqli_stmt_execute($stmt);
    $resultados = mysqli_stmt_get_result($stmt);
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
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Buscar — ReCursos</title>
    <link rel="stylesheet" href="estilos.css">
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
                        <p>Encuentra cursos sobre cualquier tema</p>
                    <?php endif; ?>
                </div>

                <!-- Filtros de búsqueda -->
                <div class="search-filters">
                    <form method="GET" action="buscar.php" class="filters-form">
                        <input type="hidden" name="q" value="<?php echo htmlspecialchars($query); ?>">
                        
                        <div class="filter-group">
                            <label for="categoria">Filtrar por categoría:</label>
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
                    </form>
                </div>

                <?php if (!empty($query)): ?>
                    <?php if (mysqli_num_rows($resultados) > 0): ?>
                        <div class="search-results">
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
                                                Por <?php echo htmlspecialchars($curso['instructor_nombre']); ?> · 
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
                    <?php else: ?>
                        <div class="empty-state">
                            <img src="assets/icons/search-empty.svg" alt="Sin resultados">
                            <h3>No se encontraron cursos</h3>
                            <p>Intenta con otros términos de búsqueda o <a href="explorar.php">explora todos los cursos</a>.</p>
                            <div class="search-suggestions">
                                <h4>Sugerencias:</h4>
                                <ul>
                                    <li>Revisa la ortografía</li>
                                    <li>Usa términos más generales</li>
                                    <li>Prueba con otras categorías</li>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <img src="assets/icons/search.svg" alt="Buscar">
                        <h3>Ingresa un término de búsqueda</h3>
                        <p>Encuentra cursos sobre programación, diseño, marketing, música y más.</p>
                        
                        <!-- Categorías populares -->
                        <div class="popular-categories">
                            <h4>Categorías populares:</h4>
                            <div class="categories-grid">
                                <a href="buscar.php?q=programacion" class="category-tag">Programación</a>
                                <a href="buscar.php?q=diseno" class="category-tag">Diseño</a>
                                <a href="buscar.php?q=marketing" class="category-tag">Marketing</a>
                                <a href="buscar.php?q=musica" class="category-tag">Música</a>
                                <a href="buscar.php?q=fotografia" class="category-tag">Fotografía</a>
                                <a href="buscar.php?q=negocios" class="category-tag">Negocios</a>
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