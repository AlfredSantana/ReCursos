<?php
session_start();
include "conexion.php";

// Verificar si se proporcionó un ID de usuario
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: explorar.php');
    exit;
}

$user_id_tercero = $_GET['id'];
$user_id_actual = $_SESSION['user_id'] ?? null;

// Obtener información del usuario tercero
$query = "SELECT *, DATE(fecha_registro) as fecha_union FROM usuarios WHERE id = ?";
$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id_tercero);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_data = mysqli_fetch_assoc($result);

// Si no existe el usuario, redirigir
if (!$user_data) {
    header('Location: explorar.php');
    exit;
}

// Usar los datos del usuario tercero
$user_name_tercero = $user_data['nombre'];
$user_email_tercero = $user_data['correo'];
$user_avatar_tercero = $user_data['avatar'];
$user_type_tercero = $user_data['tipo_usuario'] ?? 'normal';
$user_bio = $user_data['bio'] ?? '';
$user_pais = $user_data['pais'] ?? '';
$user_ciudad = $user_data['ciudad'] ?? '';
$es_instructor = $user_data['es_instructor'] ?? false;

// Obtener portada del perfil
$query_portada = "SELECT portada_url FROM portadas_perfil WHERE usuario_id = ? ORDER BY fecha_creacion DESC LIMIT 1";
$stmt_portada = mysqli_prepare($conexion, $query_portada);
mysqli_stmt_bind_param($stmt_portada, "i", $user_id_tercero);
mysqli_stmt_execute($stmt_portada);
$result_portada = mysqli_stmt_get_result($stmt_portada);
$portada_data = mysqli_fetch_assoc($result_portada);
$portada_perfil = $portada_data['portada_url'] ?? 'assets/portadas/default-portada.png';

// Obtener categorías del usuario tercero
$query_categorias_tercero = "SELECT categoria FROM usuario_categorias WHERE usuario_id = ?";
$stmt_categorias_tercero = mysqli_prepare($conexion, $query_categorias_tercero);
mysqli_stmt_bind_param($stmt_categorias_tercero, "i", $user_id_tercero);
mysqli_stmt_execute($stmt_categorias_tercero);
$categorias_tercero_result = mysqli_stmt_get_result($stmt_categorias_tercero);
$categorias_tercero = [];
while ($categoria = mysqli_fetch_assoc($categorias_tercero_result)) {
    $categorias_tercero[] = $categoria['categoria'];
}

// Obtener cursos creados por el usuario tercero
$query_cursos = "SELECT * FROM cursos WHERE instructor_id = ? ORDER BY fecha_creacion DESC";
$stmt_cursos = mysqli_prepare($conexion, $query_cursos);
mysqli_stmt_bind_param($stmt_cursos, "i", $user_id_tercero);
mysqli_stmt_execute($stmt_cursos);
$cursos_result = mysqli_stmt_get_result($stmt_cursos);
$total_creados = mysqli_num_rows($cursos_result);

// Verificar si el usuario actual es premium
$es_premium_actual = false;
if ($user_id_actual) {
    $query_premium = "SELECT tipo_usuario FROM usuarios WHERE id = ?";
    $stmt_premium = mysqli_prepare($conexion, $query_premium);
    mysqli_stmt_bind_param($stmt_premium, "i", $user_id_actual);
    mysqli_stmt_execute($stmt_premium);
    $result_premium = mysqli_stmt_get_result($stmt_premium);
    $user_actual_data = mysqli_fetch_assoc($result_premium);
    $es_premium_actual = ($user_actual_data['tipo_usuario'] === 'premium');
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title>Perfil de <?php echo htmlspecialchars($user_name_tercero); ?> — ReCursos</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="responsive.css">
    <script defer src="js/theme.js"></script>
</head>

<body>
    <?php include "componentes/header-home.php"; ?>

    <div class="page-wrap with-sidebar">
        <main class="main-content">
            <!-- Header del Perfil de Terceros -->
            <section class="profile-header">
                <div class="profile-cover">
                    <img src="<?php echo htmlspecialchars($portada_perfil); ?>" alt="Portada del perfil"
                        class="cover-image">
                </div>

                <div class="container">
                    <div class="profile-info">
                        <div class="profile-avatar-section">
                            <img src="<?php echo htmlspecialchars($user_avatar_tercero); ?>"
                                alt="Avatar de <?php echo htmlspecialchars($user_name_tercero); ?>"
                                class="profile-avatar">
                        </div>

                        <div class="profile-details">
                            <h1>
                                <?php echo htmlspecialchars($user_name_tercero); ?>
                                <?php if ($user_type_tercero === 'premium'): ?>
                                    <span class="verified-badge-profile">
                                        <img src="assets/icons/verified.svg" class="verified-icon-profile" alt="Verificado"
                                            title="Usuario Premium Verificado">
                                    </span>
                                <?php endif; ?>
                            </h1>
                            <p class="profile-email"><?php echo htmlspecialchars($user_email_tercero); ?></p>

                            <?php if (!empty($user_bio)): ?>
                                <div class="profile-bio">
                                    <p><?php echo nl2br(htmlspecialchars($user_bio)); ?></p>
                                </div>
                            <?php else: ?>
                                <div class="profile-bio empty">
                                    <p>Este usuario aún no tiene una biografía.</p>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($user_pais) || !empty($user_ciudad)): ?>
                                <div class="profile-location">
                                    <img src="assets/icons/location.svg" alt="Ubicación" class="location-icon">
                                    <span>
                                        <?php
                                        if (!empty($user_ciudad) && !empty($user_pais)) {
                                            echo htmlspecialchars($user_ciudad) . ', ' . htmlspecialchars($user_pais);
                                        } elseif (!empty($user_pais)) {
                                            echo htmlspecialchars($user_pais);
                                        } elseif (!empty($user_ciudad)) {
                                            echo htmlspecialchars($user_ciudad);
                                        }
                                        ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <!-- Categorías del usuario -->
                            <?php if (!empty($categorias_tercero)): ?>
                                <div class="usuario-categorias">
                                    <?php foreach ($categorias_tercero as $categoria): ?>
                                        <span
                                            class="categoria-badge-usuario"><?php echo ucfirst(htmlspecialchars($categoria)); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <div class="profile-badges">
                                <span class="badge-role">Estudiante</span>
                                <?php if ($es_instructor): ?>
                                    <span class="badge-instructor">Instructor</span>
                                <?php endif; ?>
                                <?php if ($user_type_tercero === 'premium'): ?>
                                    <span class="badge-premium">
                                        <img src="assets/icons/verified.svg" class="badge-premium-icon" alt="Verificado">
                                        Premium
                                    </span>
                                <?php endif; ?>
                                <span class="badge-member">Miembro desde
                                    <?php echo date('M Y', strtotime($user_data['fecha_union'] ?? 'now')); ?></span>
                            </div>

                            <div class="profile-stats">
                                <div class="profile-stat">
                                    <strong><?php echo $total_creados; ?></strong>
                                    <span>Cursos creados</span>
                                </div>
                                <div class="profile-stat">
                                    <strong>0</strong>
                                    <span>Estudiantes</span>
                                </div>
                                <div class="profile-stat">
                                    <strong>4.8</strong>
                                    <span>Calificación</span>
                                </div>
                            </div>
                        </div>

                        <!-- En perfil de terceros NO mostramos acciones de edición -->
                        <div class="profile-actions">
                            <!-- Podrías añadir aquí un botón para seguir al usuario o enviar mensaje -->
                            <button class="profile-icon-btn"
                                onclick="alert('Funcionalidad de seguir usuario próximamente')">
                                <img src="assets/icons/follow.svg" alt="follow-icon">
                                Seguir
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Cursos del Usuario -->
            <section class="container">
                <div class="section-header">
                    <h2>Cursos de <?php echo htmlspecialchars($user_name_tercero); ?></h2>
                </div>

                <?php if (mysqli_num_rows($cursos_result) > 0): ?>
                    <div class="cursos-grid">
                        <?php while ($curso = mysqli_fetch_assoc($cursos_result)):
                            // Calcular precio con descuento si el usuario actual es premium
                            $precio_original = $curso['precio'];
                            $precio_final = $precio_original;
                            $descuento = 0;

                            if ($es_premium_actual && $precio_original > 0) {
                                $descuento = 20; // 20% de descuento para premium
                                $precio_final = $precio_original * 0.8;
                            }
                            ?>
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
                                        <?php if ($precio_original == 0): ?>
                                            <!-- CURSO GRATIS -->
                                            <div class="price-action-layout">
                                                <div class="price-section">
                                                    <span class="price free">Gratis</span>
                                                </div>
                                                <div class="action-section">
                                                    <a href="curso.php?id=<?php echo $curso['id']; ?>" class="btn-compact">Tomar</a>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <?php if ($es_premium_actual): ?>
                                                <!-- USUARIO PREMIUM CON DESCUENTO -->
                                                <div class="price-action-layout">
                                                    <div class="price-section">
                                                        <div class="price-stack">
                                                            <span class="price-old">RD$
                                                                <?php echo number_format($precio_original, 0, '.', ','); ?></span>
                                                            <div class="price-current-group">
                                                                <span class="price-current">RD$
                                                                    <?php echo number_format($precio_final, 0, '.', ','); ?></span>
                                                                <span class="discount-pill">-<?php echo $descuento; ?>%</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="action-section">
                                                        <a href="comprar-curso.php?id=<?php echo $curso['id']; ?>"
                                                            class="btn-compact">Comprar</a>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <!-- USUARIO NORMAL -->
                                                <div class="price-action-layout">
                                                    <div class="price-section">
                                                        <div class="price-stack">
                                                            <span class="price-current">RD$
                                                                <?php echo number_format($precio_original, 0, '.', ','); ?></span>
                                                            <div class="premium-hint">
                                                                <span class="premium-tag">Ahorra 20% con Premium</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="action-section">
                                                        <a href="comprar-curso.php?id=<?php echo $curso['id']; ?>"
                                                            class="btn-compact">Comprar</a>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <img src="assets/icons/course-empty.svg" alt="Sin cursos">
                        <h3>Este usuario aún no ha creado cursos</h3>
                        <p>Cuando <?php echo htmlspecialchars($user_name_tercero); ?> cree cursos, aparecerán aquí.</p>
                    </div>
                <?php endif; ?>
            </section>
        </main>

        <!-- SIDEBAR DERECHO -->
        <aside class="sidebar-right">
            <?php include "componentes/sidebar-secciones.php"; ?>
            <?php include "componentes/cursos-recomendados-sidebar.php"; ?>
            <?php include "componentes/instructores-sidebar.php"; ?>
            <?php include "componentes/sidebar-cursos-destacados.php"; ?>
        </aside>
    </div>

    <?php include "componentes/footer.php"; ?>
</body>

</html>