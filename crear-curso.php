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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $categoria = $_POST['categoria'];
    $nivel = $_POST['nivel'];
    $duracion = $_POST['duracion'];
    $precio = $_POST['precio'];

    // Procesar imagen del curso
    $imagen_path = 'assets/cursos/curso-default.jpg';

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen = $_FILES['imagen'];

        // Validar que sea una imagen
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (in_array($imagen['type'], $allowed_types)) {

            // Crear directorio si no existe
            if (!is_dir('assets/cursos/imagenes/')) {
                mkdir('assets/cursos/imagenes/', 0777, true);
            }

            // Generar nombre único para la imagen
            $extension = pathinfo($imagen['name'], PATHINFO_EXTENSION);
            $imagen_filename = 'curso_' . time() . '_' . uniqid() . '.' . $extension;
            $imagen_path = 'assets/cursos/imagenes/' . $imagen_filename;

            // Mover el archivo
            if (!move_uploaded_file($imagen['tmp_name'], $imagen_path)) {
                $error = "Error al subir la imagen del curso.";
                $imagen_path = 'assets/cursos/curso-default.jpg';
            }
        } else {
            $error = "Solo se permiten imágenes JPG, PNG, GIF o WEBP.";
        }
    }

    if (empty($error)) {
        if (empty($titulo) || empty($descripcion)) {
            $error = "El título y la descripción son obligatorios.";
        } else {
            // Insertar el curso
            $stmt = mysqli_prepare(
                $conexion,
                "INSERT INTO cursos (titulo, descripcion, imagen, instructor_id, instructor_nombre, duracion_horas, precio, categoria, nivel) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );

            mysqli_stmt_bind_param($stmt, "sssisddss", $titulo, $descripcion, $imagen_path, $user_id, $user_name, $duracion, $precio, $categoria, $nivel);

            if (mysqli_stmt_execute($stmt)) {
                $success = "¡Curso creado exitosamente!";
                $_POST = array(); // Limpiar formulario
            } else {
                $error = "Error al crear el curso: " . mysqli_error($conexion);
            }

            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title>Crear Curso — ReCursos</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="responsive.css">
    <script defer src="js/theme.js"></script>
</head>

<body>
    <?php include "componentes/header-home.php"; ?>

    <div class="page-wrap">
        <main class="main-content">
            <section class="container">
                <div class="form-header">
                    <h1>Crear Nuevo Curso</h1>
                    <p>Comparte tu conocimiento con la comunidad de ReCursos</p>
                    <p><strong>Instructor:</strong> <?php echo htmlspecialchars($user_name); ?></p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="success-message"><?php echo $success; ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="course-form">
                    <div class="form-group">
                        <label for="titulo">Título del Curso *</label>
                        <input type="text" id="titulo" name="titulo" required
                            value="<?php echo $_POST['titulo'] ?? ''; ?>" placeholder="Ej: Introducción a Python">
                    </div>

                    <div class="form-group">
                        <label for="descripcion">Descripción *</label>
                        <textarea id="descripcion" name="descripcion" rows="5" required
                            placeholder="Describe qué aprenderán los estudiantes..."><?php echo $_POST['descripcion'] ?? ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="imagen">Imagen del Curso</label>
                        <input type="file" id="imagen" name="imagen" accept="image/*">
                        <small>Recomendado: 1280x720 px. Formatos: JPG, PNG, GIF, WEBP</small>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="categoria">Categoría</label>
                            <select id="categoria" name="categoria">
                                <option value="programacion">Programación</option>
                                <option value="diseno">Diseño</option>
                                <option value="marketing">Marketing</option>
                                <option value="musica">Música</option>
                                <option value="fotografia">Fotografía</option>
                                <option value="negocios">Negocios</option>
                                <option value="idiomas">Idiomas</option>
                                <option value="otros">Otros</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="nivel">Nivel</label>
                            <select id="nivel" name="nivel">
                                <option value="principiante">Principiante</option>
                                <option value="intermedio">Intermedio</option>
                                <option value="avanzado">Avanzado</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="duracion">Duración (horas)</label>
                            <input type="number" id="duracion" name="duracion"
                                value="<?php echo $_POST['duracion'] ?? '1'; ?>" min="0.5" max="100" step="0.5">
                        </div>

                        <div class="form-group">
                            <label for="precio">Precio (RD$)</label>
                            <input type="number" id="precio" name="precio"
                                value="<?php echo $_POST['precio'] ?? '0'; ?>" min="0" step="50">
                            <small>0 = Curso gratuito</small>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="perfil.php" class="btn btn-outline">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Crear Curso</button>
                    </div>
                </form>
            </section>
        </main>
    </div>

    <?php include "componentes/footer.php"; ?>
</body>

</html>