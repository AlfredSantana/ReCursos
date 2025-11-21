<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include "conexion.php";

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'Usuario';
$error = '';
$success = '';

// Obtener información actual del usuario
$query = "SELECT * FROM usuarios WHERE id = ?";
$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_data = mysqli_fetch_assoc($result);

// Obtener portada actual
$query_portada = "SELECT portada_url FROM portadas_perfil WHERE usuario_id = ? ORDER BY fecha_creacion DESC LIMIT 1";
$stmt_portada = mysqli_prepare($conexion, $query_portada);
mysqli_stmt_bind_param($stmt_portada, "i", $user_id);
mysqli_stmt_execute($stmt_portada);
$result_portada = mysqli_stmt_get_result($stmt_portada);
$portada_data = mysqli_fetch_assoc($result_portada);
$portada_perfil = $portada_data['portada_url'] ?? 'assets/portadas/default-portada.png';

if (empty($error)) {
    // Obtener los nuevos datos de ubicación
    $pais = trim($_POST['pais'] ?? '');
    $ciudad = trim($_POST['ciudad'] ?? '');

    // Actualizar datos del usuario (AGREGAR pais y ciudad)
    $update_query = "UPDATE usuarios SET nombre = ?, correo = ?, bio = ?, avatar = ?, pais = ?, ciudad = ? WHERE id = ?";
    $update_stmt = mysqli_prepare($conexion, $update_query);
    mysqli_stmt_bind_param($update_stmt, "ssssssi", $nombre, $email, $bio, $avatar_path, $pais, $ciudad, $user_id);
    // Nota: Cambiamos de "ssssi" a "ssssssi" por los 2 campos nuevos

    if (mysqli_stmt_execute($update_stmt)) {
        // Actualizar datos en la sesión
        $_SESSION['user_name'] = $nombre;
        $_SESSION['user_email'] = $email;

        $success = "Perfil actualizado correctamente.";

        // Actualizar datos locales
        $user_data['nombre'] = $nombre;
        $user_data['correo'] = $email;
        $user_data['bio'] = $bio;
        $user_data['avatar'] = $avatar_path;
        $user_data['pais'] = $pais;
        $user_data['ciudad'] = $ciudad;
    } else {
        $error = "Error al actualizar el perfil: " . mysqli_error($conexion);
    }

    mysqli_stmt_close($update_stmt);
}

// Procesar actualización de perfil
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $bio = trim($_POST['bio']);

    // Procesar avatar si se subió uno nuevo
    $avatar_path = $user_data['avatar'];

    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $avatar = $_FILES['avatar'];

        // Validar que sea una imagen
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (in_array($avatar['type'], $allowed_types)) {

            // Crear directorio si no existe
            if (!is_dir('assets/usuarios/avatars/')) {
                mkdir('assets/usuarios/avatars/', 0777, true);
            }

            // Generar nombre único para la imagen
            $extension = pathinfo($avatar['name'], PATHINFO_EXTENSION);
            $avatar_filename = 'user_' . $user_id . '_' . time() . '.' . $extension;
            $avatar_path = 'assets/usuarios/avatars/' . $avatar_filename;

            // Mover el archivo
            if (move_uploaded_file($avatar['tmp_name'], $avatar_path)) {
                // Eliminar avatar anterior si no es el default
                if ($user_data['avatar'] !== 'assets/usuarios/user-default.avif' && file_exists($user_data['avatar'])) {
                    unlink($user_data['avatar']);
                }
            } else {
                $error = "Error al subir la imagen.";
            }
        } else {
            $error = "Solo se permiten imágenes JPG, PNG, GIF o WEBP.";
        }
    }

    // Procesar portada si se subió una nueva
    if (isset($_FILES['portada']) && $_FILES['portada']['error'] === UPLOAD_ERR_OK) {
        $portada = $_FILES['portada'];

        // Validar que sea una imagen
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (in_array($portada['type'], $allowed_types)) {

            // Crear directorio si no existe
            if (!is_dir('assets/portadas/')) {
                mkdir('assets/portadas/', 0777, true);
            }

            // Generar nombre único para la imagen
            $extension = pathinfo($portada['name'], PATHINFO_EXTENSION);
            $portada_filename = 'portada_' . $user_id . '_' . time() . '.' . $extension;
            $portada_path = 'assets/portadas/' . $portada_filename;

            // Mover el archivo
            if (move_uploaded_file($portada['tmp_name'], $portada_path)) {
                // Insertar en tabla portadas_perfil
                $insert_portada = "INSERT INTO portadas_perfil (usuario_id, portada_url) VALUES (?, ?)";
                $stmt_portada = mysqli_prepare($conexion, $insert_portada);
                mysqli_stmt_bind_param($stmt_portada, "is", $user_id, $portada_path);
                mysqli_stmt_execute($stmt_portada);
            } else {
                $error = "Error al subir la portada.";
            }
        } else {
            $error = "Solo se permiten imágenes JPG, PNG, GIF o WEBP para la portada.";
        }
    }

    if (empty($error)) {
        // Actualizar datos del usuario
        $update_query = "UPDATE usuarios SET nombre = ?, correo = ?, bio = ?, avatar = ? WHERE id = ?";
        $update_stmt = mysqli_prepare($conexion, $update_query);
        mysqli_stmt_bind_param($update_stmt, "ssssi", $nombre, $email, $bio, $avatar_path, $user_id);

        if (mysqli_stmt_execute($update_stmt)) {
            // Actualizar datos en la sesión
            $_SESSION['user_name'] = $nombre;
            $_SESSION['user_email'] = $email;

            $success = "Perfil actualizado correctamente.";

            // Actualizar datos locales
            $user_data['nombre'] = $nombre;
            $user_data['correo'] = $email;
            $user_data['bio'] = $bio;
            $user_data['avatar'] = $avatar_path;
        } else {
            $error = "Error al actualizar el perfil: " . mysqli_error($conexion);
        }

        mysqli_stmt_close($update_stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title>Editar Perfil — ReCursos</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="responsive.css">
    <script defer src="js/theme.js"></script>
    <script defer src="js/menu.js"></script>
</head>

<body>
    <?php include "componentes/header-home.php"; ?>

    <div class="page-wrap">
        <main class="main-content">
            <section class="container">
                <div class="form-header">
                    <h1>Editar Perfil</h1>
                    <p>Actualiza tu información personal</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="success-message"><?php echo $success; ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="profile-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="avatar">Foto de Perfil</label>
                            <div class="avatar-upload">
                                <img src="<?php echo htmlspecialchars($user_data['avatar']); ?>" alt="Avatar actual"
                                    class="current-avatar" id="avatar-preview">
                                <input type="file" id="avatar" name="avatar" accept="image/*"
                                    onchange="previewImage(this)">
                                <label for="avatar" class="btn btn-outline">Cambiar Foto</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="nombre">Nombre Completo *</label>
                            <input type="text" id="nombre" name="nombre" required
                                value="<?php echo htmlspecialchars($user_data['nombre'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Correo Electrónico *</label>
                        <input type="email" id="email" name="email" required
                            value="<?php echo htmlspecialchars($user_data['correo'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="bio">Biografía</label>
                        <textarea id="bio" name="bio" rows="4"
                            placeholder="Cuéntanos sobre ti..."><?php echo htmlspecialchars($user_data['bio'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="pais">País</label>
                            <input type="text" id="pais" name="pais"
                                value="<?php echo htmlspecialchars($user_data['pais'] ?? ''); ?>"
                                placeholder="Ej: República Dominicana">
                        </div>

                        <div class="form-group">
                            <label for="ciudad">Ciudad</label>
                            <input type="text" id="ciudad" name="ciudad"
                                value="<?php echo htmlspecialchars($user_data['ciudad'] ?? ''); ?>"
                                placeholder="Ej: Santo Domingo">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="portada">Portada del Perfil</label>
                        <div class="portada-upload">
                            <img src="<?php echo htmlspecialchars($portada_perfil ?? 'assets/portadas/default-portada.png'); ?>"
                                alt="Portada actual" class="current-portada" id="portada-preview">
                            <input type="file" id="portada" name="portada" accept="image/*"
                                onchange="previewPortada(this)">
                            <label for="portada" class="btn btn-outline">Cambiar Portada</label>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="perfil.php" class="btn btn-outline">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </section>
        </main>
    </div>

    <?php include "componentes/footer.php"; ?>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewPortada(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('portada-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>