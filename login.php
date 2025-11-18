<?php
include "conexion.php";
session_start();
$error = "";

// Detectar si viene de premium
$action = $_GET['action'] ?? '';
$special_message = '';

if ($action === 'premium') {
    $special_message = "Inicia sesión o regístrate para adquirir Premium";
} else {
    $special_message = "Bienvenido de vuelta a tu plataforma de aprendizaje.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = trim($_POST['correo']);
    $pass = $_POST['pass'];
    $pass_hash = MD5($pass);

    $query = "SELECT id, nombre, correo, tipo_usuario FROM usuarios WHERE correo = '$correo' AND pass = '$pass_hash'";
    $result = mysqli_query($conexion, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $usuario = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['user_name'] = $usuario['nombre'];
        $_SESSION['user_email'] = $usuario['correo'];
        $_SESSION['user_type'] = $usuario['tipo_usuario'];
        $_SESSION['logged_in'] = true;

        // Redirigir a suscripcion.php si venía de premium
        if ($action === 'premium') {
            header("Location: suscripcion.php");
        } else {
            header("Location: home.php");
        }
        exit();
    } else {
        $error = "Correo o contraseña incorrectos. Por favor, intenta nuevamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Iniciar Sesión — ReCursos</title>
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

            <h2>Iniciar Sesión</h2>
            <p class="register-subtext"><?php echo $special_message; ?></p>

            <?php if (!empty($error)) {
                echo "<p class='error-message'>$error</p>";
            } ?>

            <form method="POST" class="login-form">
                <label for="correo">Correo electrónico *</label>
                <input class="input" type="email" id="correo" name="correo" placeholder="nombre@correoelectronico.com"
                    required value="<?php echo isset($_POST['correo']) ? htmlspecialchars($_POST['correo']) : ''; ?>">

                <label for="pass">Contraseña *</label>
                <div class="password-wrap">
                    <input class="input" type="password" id="pass" name="pass" placeholder="Ingresa tu contraseña"
                        required>
                    <span class="eye-icon" data-target="pass">
                        <img src="assets/icons/eye-open.svg" alt="Mostrar contraseña">
                    </span>
                </div>

                <a href="forgot-password.php" class="forgot-pass-link">¿Olvidaste tu contraseña?</a>

                <button class="btn btn-large" type="submit">Iniciar Sesión</button>
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
                ¿No tienes una cuenta? <a href="register.php">Regístrate gratis</a>
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