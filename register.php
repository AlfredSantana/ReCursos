<?php
include "conexion.php";
session_start();
$error = "";

// Detectar si viene de premium
$action = $_GET['action'] ?? '';
$special_message = '';

if ($action === 'premium') {
    $special_message = "Regístrate para adquirir Premium y acceder a todos los beneficios";
} else {
    $special_message = "Aprende a tu propio ritmo y certifícate con ReCursos.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $pass = $_POST['pass'];
    $confirm_pass = $_POST['confirm_pass'];

    // Validaciones adicionales
    if (empty($nombre) || empty($correo) || empty($pass)) {
        $error = "Todos los campos marcados con * son obligatorios.";
    } elseif ($pass !== $confirm_pass) {
        $error = "Las contraseñas no coinciden. Por favor, revísalas.";
    } elseif (strlen($pass) < 8) {
        $error = "La contraseña debe tener al menos 8 caracteres.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = "El formato del correo electrónico no es válido.";
    } else {
        // Usar sentencias preparadas para prevenir SQL injection
        $stmt = mysqli_prepare($conexion, "INSERT INTO usuarios (nombre, correo, pass) VALUES (?, ?, MD5(?))");

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $nombre, $correo, $pass);

            if (mysqli_stmt_execute($stmt)) {
                // Obtener el ID del usuario recién insertado
                $user_id = mysqli_insert_id($conexion);

                // Iniciar sesión automáticamente
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $nombre;
                $_SESSION['user_email'] = $correo;
                $_SESSION['logged_in'] = true;

                // Redirigir a home.php
                header("Location: home.php");
                exit();
            } else {
                if (mysqli_errno($conexion) == 1062) {
                    $error = "Este correo ya está registrado.";
                } else {
                    $error = "Error al registrar: " . mysqli_error($conexion);
                }
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = "Error en la preparación de la consulta: " . mysqli_error($conexion);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Regístrate — ReCursos</title>
    <link rel="stylesheet" href="estilos.css">
    <script defer src="js/theme.js"></script>
</head>

<body class="login-body">

    <?php include "componentes/header-register.php"; ?>

    <section class="auth-page">
        <div class="login-card">

            <div class="logo-wrap logo-center">
                <img src="assets/logo/logo3sf.png" class="logo" alt="ReCursos">
            </div>

            <h2>Regístrate</h2>
            <p class="register-subtext"><?php echo htmlspecialchars($special_message); ?></p>

            <?php if (!empty($error)) {
                echo "<p class='error-message'>" . htmlspecialchars($error) . "</p>";
            } ?>

            <form method="POST" class="login-form">
                <label for="nombre">Nombre Completo *</label>
                <input class="input" type="text" id="nombre" name="nombre" placeholder="Ingresa tu nombre completo"
                    required value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>">

                <label for="correo">Correo electrónico *</label>
                <input class="input" type="email" id="correo" name="correo" placeholder="nombre@correoelectronico.com"
                    required value="<?php echo isset($_POST['correo']) ? htmlspecialchars($_POST['correo']) : ''; ?>">

                <label for="pass">Contraseña *</label>
                <div class="password-wrap">
                    <input class="input" type="password" id="pass" name="pass" placeholder="Entre 8 y 72 caracteres"
                        required minlength="8">
                    <span class="eye-icon" data-target="pass">
                        <img src="assets/icons/eye-open.svg" alt="Mostrar contraseña">
                    </span>
                </div>

                <label for="confirm_pass">Confirmar Contraseña *</label>
                <div class="password-wrap">
                    <input class="input" type="password" id="confirm_pass" name="confirm_pass"
                        placeholder="Confirma tu contraseña" required>
                    <span class="eye-icon" data-target="confirm_pass">
                        <img src="assets/icons/eye-open.svg" alt="Mostrar contraseña">
                    </span>
                </div>

                <button class="btn btn-large" type="submit">Únete de forma gratuita</button>
            </form>

            <div class="divider"><span>o</span></div>

            <div class="social-login">
                <button class="btn btn-social">
                    <img src="assets/icons/google.svg" alt="Google">
                    Continuar con Google
                </button>
                <button class="btn btn-social">
                    <img src="assets/icons/facebook-color.svg" alt="Facebook">
                    Continuar con Facebook
                </button>
                <button class="btn btn-social">
                    <img src="assets/icons/apple.svg" alt="Apple" class="social-icon-apple">
                    Continuar con Apple
                </button>
            </div>

            <p class="register-note">
                ¿Ya formas parte de ReCursos? <a href="login.php">Iniciar sesión</a>
            </p>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const eyeIcons = document.querySelectorAll('.eye-icon');

            eyeIcons.forEach(icon => {
                icon.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-target');
                    const passwordInput = document.getElementById(targetId);

                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        this.innerHTML = '<img src="assets/icons/eye-closed.svg" alt="Ocultar contraseña">';
                    } else {
                        passwordInput.type = 'password';
                        this.innerHTML = '<img src="assets/icons/eye-open.svg" alt="Mostrar contraseña">';
                    }
                });
            });
        });
    </script>

    <?php include "componentes/footer.php"; ?>

</body>

</html>