<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include "conexion.php";

$user_id = $_SESSION['user_id'];

// MANEJAR SUBIDA DE PORTADA
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['portada'])) {
    $file = $_FILES['portada'];

    // Validar que sea una imagen
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024; // 5MB

    if (in_array($file['type'], $allowed_types) && $file['size'] <= $max_size) {
        // Crear directorio si no existe
        $upload_dir = 'assets/portadas/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Generar nombre único
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $file_name = 'portada_' . $user_id . '_' . time() . '.' . $file_extension;
        $file_path = $upload_dir . $file_name;

        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            // Insertar en la base de datos
            $insert_query = "INSERT INTO portadas_perfil (usuario_id, portada_url) VALUES (?, ?)";
            $stmt = mysqli_prepare($conexion, $insert_query);
            mysqli_stmt_bind_param($stmt, "is", $user_id, $file_path);

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['success'] = "Portada actualizada correctamente";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            } else {
                $_SESSION['error'] = "Error al guardar en la base de datos";
            }

            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['error'] = "Error al subir la imagen";
        }
    } else {
        $_SESSION['error'] = "Formato no válido o archivo muy grande (máx. 5MB)";
    }
}

// Mostrar mensajes de éxito/error
if (isset($_SESSION['success'])) {
    $success_message = $_SESSION['success'];
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    $error_message = $_SESSION['error'];
    unset($_SESSION['error']);
}

// Obtener información actualizada del usuario (solo una vez)
$query = "SELECT *, DATE(fecha_registro) as fecha_union FROM usuarios WHERE id = ?";
$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_data = mysqli_fetch_assoc($result);

// Actualizar variables de sesión con datos actualizados
$_SESSION['user_name'] = $user_data['nombre'];
$_SESSION['user_email'] = $user_data['correo'];
$_SESSION['user_avatar'] = $user_data['avatar'];

$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];
$user_avatar = $_SESSION['user_avatar'];
$user_type = $_SESSION['user_type'] ?? 'normal';
$user_bio = $user_data['bio'] ?? '';
$user_pais = $user_data['pais'] ?? '';
$user_ciudad = $user_data['ciudad'] ?? '';
$portada_perfil = $user_data['portada_perfil'] ?? 'assets/portadas/default-portada.png';
$es_instructor = $user_data['es_instructor'] ?? false;

// Obtener portada del perfil desde la tabla separada
$query_portada = "SELECT portada_url FROM portadas_perfil WHERE usuario_id = ? ORDER BY fecha_creacion DESC LIMIT 1";
$stmt_portada = mysqli_prepare($conexion, $query_portada);
mysqli_stmt_bind_param($stmt_portada, "i", $user_id);
mysqli_stmt_execute($stmt_portada);
$result_portada = mysqli_stmt_get_result($stmt_portada);
$portada_data = mysqli_fetch_assoc($result_portada);

$portada_perfil = $portada_data['portada_url'] ?? 'assets/portadas/default-portada.png';

// Obtener cursos creados por el usuario
$query_cursos = "SELECT * FROM cursos WHERE instructor_id = ? ORDER BY fecha_creacion DESC";
$stmt_cursos = mysqli_prepare($conexion, $query_cursos);
mysqli_stmt_bind_param($stmt_cursos, "i", $user_id);
mysqli_stmt_execute($stmt_cursos);
$cursos_result = mysqli_stmt_get_result($stmt_cursos);

// Contar total de cursos creados
$total_creados = mysqli_num_rows($cursos_result);

// Si tiene cursos creados, actualizar el estado de instructor
if ($total_creados > 0 && !$es_instructor) {
    $update_instructor = "UPDATE usuarios SET es_instructor = TRUE WHERE id = ?";
    $stmt_update = mysqli_prepare($conexion, $update_instructor);
    mysqli_stmt_bind_param($stmt_update, "i", $user_id);
    mysqli_stmt_execute($stmt_update);
    $es_instructor = true;
}

// Obtener algunos cursos populares para mostrar (simulando "cursos tomados")
$query_cursos_populares = "SELECT * FROM cursos WHERE instructor_id != ? ORDER BY fecha_creacion DESC LIMIT 3";
$stmt_populares = mysqli_prepare($conexion, $query_cursos_populares);
mysqli_stmt_bind_param($stmt_populares, "i", $user_id);
mysqli_stmt_execute($stmt_populares);
$cursos_populares = mysqli_stmt_get_result($stmt_populares);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title>Mi Perfil — ReCursos</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="responsive.css">
    <script defer src="js/theme.js"></script>
    <script defer src="js/menu.js"></script>
</head>

<body>

    <?php include "componentes/header-home.php"; ?>

    <div class="page-wrap">
        <main class="main-content">

            <!-- Header del Perfil -->
            <section class="profile-header">
                <!-- Portada del perfil -->
                <!-- Portada del perfil -->
                <div class="profile-cover">
                    <img src="<?php echo htmlspecialchars($portada_perfil); ?>" alt="Portada del perfil"
                        class="cover-image" id="cover-image">

                    <!-- Formulario para subir portada -->
                    <form id="cover-form" action="perfil.php" method="POST" enctype="multipart/form-data"
                        style="display: none;">
                        <input type="file" id="cover-upload" name="portada" accept="image/*">
                    </form>

                    <button class="btn-cover-edit" onclick="document.getElementById('cover-upload').click()"
                        title="Cambiar portada">
                        <img src="assets/icons/edit.svg" alt="Cambiar portada">
                    </button>
                </div>

                <div class="container">
                    <div class="profile-info">
                        <div class="profile-avatar-section">
                            <img src="<?php echo htmlspecialchars($user_avatar); ?>" alt="Avatar" class="profile-avatar"
                                id="profile-avatar">
                            <input type="file" id="avatar-upload" accept="image/*" style="display: none;">
                            <button class="btn-avatar-edit" onclick="document.getElementById('avatar-upload').click()">
                                <img src="assets/icons/camera.svg" alt="Cambiar foto">
                            </button>
                        </div>

                        <div class="profile-details">
                            <h1>
                                <?php echo htmlspecialchars($user_name); ?>
                                <?php if ($user_type === 'premium'): ?>
                                    <span class="verified-badge-profile">
                                        <img src="assets/icons/verified.svg" class="verified-icon-profile" alt="Verificado"
                                            title="Usuario Premium Verificado">
                                    </span>
                                <?php endif; ?>
                            </h1>
                            <p class="profile-email"><?php echo htmlspecialchars($user_email); ?></p>

                            <!-- MOSTRAR BIO AQUÍ -->
                            <?php if (!empty($user_bio)): ?>
                                <div class="profile-bio">
                                    <p><?php echo nl2br(htmlspecialchars($user_bio)); ?></p>
                                </div>
                            <?php else: ?>
                                <div class="profile-bio empty">
                                    <p>¡Aún no tienes una biografía! <a href="editar-perfil.php">Agrega una</a> para que
                                        otros usuarios te conozcan mejor.</p>
                                </div>
                            <?php endif; ?>

                            <!-- Ubicación -->
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

                            <div class="profile-badges">
                                <span class="badge-role">Estudiante</span>
                                <?php if ($es_instructor): ?>
                                    <span class="badge-instructor">
                                        Instructor
                                    </span>
                                <?php endif; ?>
                                <?php if ($user_type === 'premium'): ?>
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
                                    <strong>3</strong>
                                    <span>Cursos tomados</span>
                                </div>
                                <div class="profile-stat">
                                    <strong><?php echo $total_creados; ?></strong>
                                    <span>Cursos creados</span>
                                </div>
                                <div class="profile-stat">
                                    <strong>12h</strong>
                                    <span>Horas aprendidas</span>
                                </div>
                            </div>
                        </div>

                        <div class="profile-actions">
                            <a href="editar-perfil.php" class="profile-icon-btn">
                                <img src="assets/icons/edit-profile.svg" alt="">
                                Editar Perfil
                            </a>

                            <a href="crear-curso.php" class="profile-icon-btn primary">
                                <img src="assets/icons/add-course.svg" alt="">
                                Crear Curso
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Contenido con Pestañas -->
            <section class="container">
                <div class="tabs">
                    <button class="tab-btn active" data-tab="cursos-tomados">Cursos Recomendados</button>
                    <button class="tab-btn" data-tab="cursos-creados">Mis Cursos Creados</button>
                    <button class="tab-btn" data-tab="configuracion">Configuración</button>
                </div>

                <!-- Pestaña de Cursos Recomendados -->
                <div class="tab-content active" id="cursos-tomados">
                    <div class="section-header">
                        <h2>Cursos Recomendados</h2>
                        <a href="explorar.php" class="view-all">Ver todos</a>
                    </div>

                    <?php if (mysqli_num_rows($cursos_populares) > 0): ?>
                        <div class="cursos-grid">
                            <?php while ($curso = mysqli_fetch_assoc($cursos_populares)): ?>
                                <article class="card-curso">
                                    <img src="<?php echo htmlspecialchars($curso['imagen']); ?>"
                                        alt="<?php echo htmlspecialchars($curso['titulo']); ?>" loading="lazy">
                                    <div class="card-body">
                                        <h3><?php echo htmlspecialchars($curso['titulo']); ?></h3>
                                        <p class="meta">Por <?php echo htmlspecialchars($curso['instructor_nombre']); ?> ·
                                            <?php echo $curso['duracion_horas']; ?> horas
                                        </p>
                                        <p class="desc"><?php echo htmlspecialchars($curso['descripcion']); ?></p>
                                        <div class="card-foot">
                                            <span
                                                class="price"><?php echo ($curso['precio'] == 0) ? 'Gratis' : 'RD$ ' . $curso['precio']; ?></span>
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
                            <p>Pronto tendremos cursos recomendados para ti.</p>
                            <a href="crear-curso.php" class="btn btn-primary">Crear Primer Curso</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pestaña de Cursos Creados -->
                <div class="tab-content" id="cursos-creados">
                    <div class="section-header">
                        <h2>Mis Cursos Creados</h2>
                        <a href="crear-curso.php" class="btn btn-primary">Crear Nuevo Curso</a>
                    </div>

                    <?php if (mysqli_num_rows($cursos_result) > 0): ?>
                        <div class="cursos-grid">
                            <?php while ($curso = mysqli_fetch_assoc($cursos_result)): ?>
                                <article class="card-curso">
                                    <img src="<?php echo htmlspecialchars($curso['imagen']); ?>"
                                        alt="<?php echo htmlspecialchars($curso['titulo']); ?>" loading="lazy">
                                    <div class="card-body">
                                        <h3><?php echo htmlspecialchars($curso['titulo']); ?></h3>
                                        <p class="meta"><?php echo $curso['duracion_horas']; ?> horas ·
                                            <?php echo ucfirst($curso['nivel']); ?>
                                        </p>
                                        <p class="desc"><?php echo htmlspecialchars($curso['descripcion']); ?></p>
                                        <div class="card-foot">
                                            <span
                                                class="price"><?php echo ($curso['precio'] == 0) ? 'Gratis' : 'RD$ ' . $curso['precio']; ?></span>
                                            <div class="card-actions">
                                                <a href="editar-curso.php?id=<?php echo $curso['id']; ?>"
                                                    class="link">Editar</a>
                                                <a href="curso.php?id=<?php echo $curso['id']; ?>" class="link">Ver</a>
                                            </div>
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
                            <a href="crear-curso.php" class="btn btn-primary">Crear mi primer curso</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pestaña de Configuración -->
                <div class="tab-content" id="configuracion">
                    <div class="empty-state">
                        <img src="assets/icons/settings.svg" alt="Configuración">
                        <h3>Configuración de cuenta</h3>
                        <p>Gestiona la configuración de tu cuenta y preferencias.</p>
                        <a href="editar-perfil.php" class="btn btn-primary">Editar Perfil</a>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <?php include "componentes/footer.php"; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // JavaScript para las pestañas
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Remover active de todos
                    tabBtns.forEach(b => b.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));

                    // Agregar active al seleccionado
                    btn.classList.add('active');
                    const tabId = btn.getAttribute('data-tab');
                    const tabContent = document.getElementById(tabId);
                    if (tabContent) {
                        tabContent.classList.add('active');
                    }
                });
            });

            // Upload de avatar
            document.getElementById('avatar-upload').addEventListener('change', function (e) {
                if (this.files && this.files[0]) {
                    // Aquí puedes agregar la funcionalidad para subir avatar
                    // Por ahora mantenemos la vista previa
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById('profile-avatar').src = e.target.result;
                    }
                    reader.readAsDataURL(this.files[0]);

                    // Para subir el avatar necesitarías un formulario similar al de portada
                    alert('Para cambiar el avatar, ve a "Editar Perfil"');
                    this.value = ''; // Limpiar el input
                }
            });

            // Upload de portada (FUNCIONALIDAD COMPLETA)
            document.getElementById('cover-upload').addEventListener('change', function (e) {
                if (this.files && this.files[0]) {
                    // Validar tipo de archivo
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    if (!allowedTypes.includes(this.files[0].type)) {
                        alert('Por favor, sube una imagen válida (JPEG, PNG, GIF, WebP)');
                        this.value = '';
                        return;
                    }

                    // Validar tamaño (5MB)
                    if (this.files[0].size > 5 * 1024 * 1024) {
                        alert('La imagen es muy grande. Máximo 5MB permitidos.');
                        this.value = '';
                        return;
                    }

                    // Mostrar vista previa
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById('cover-image').src = e.target.result;
                    }
                    reader.readAsDataURL(this.files[0]);

                    // Enviar formulario automáticamente
                    document.getElementById('cover-form').submit();
                }
            });

            // Mostrar mensajes
            <?php if (isset($success_message)): ?>
                alert('<?php echo $success_message; ?>');
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                alert('<?php echo $error_message; ?>');
            <?php endif; ?>
        });
    </script>
</body>

</html>