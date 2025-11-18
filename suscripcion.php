<?php
// suscripcion.php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include "conexion.php";

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'] ?? 'normal';

// Procesar actualización a premium
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    if ($_POST['action'] === 'upgrade') {
        $query = "UPDATE usuarios SET tipo_usuario = 'premium' WHERE id = $user_id";
        if (mysqli_query($conexion, $query)) {
            $_SESSION['user_type'] = 'premium';
            $user_type = 'premium';
            $success = "¡Felicidades! Ahora eres usuario Premium.";
        }
    } elseif ($_POST['action'] === 'cancel') {
        $query = "UPDATE usuarios SET tipo_usuario = 'normal' WHERE id = $user_id";
        if (mysqli_query($conexion, $query)) {
            $_SESSION['user_type'] = 'normal';
            $user_type = 'normal';
            $success = "Plan Premium cancelado correctamente.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Premium — ReCursos</title>
    <link rel="stylesheet" href="estilos.css">
    <script defer src="js/theme.js"></script>
</head>

<body>
    <?php include "componentes/header-home.php"; ?>

    <div class="page-wrap">
        <section class="premium-section">
            <h2>Gestionar Suscripción Premium</h2>

            <?php if (isset($success)): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>

            <div class="premium-plans">
                <?php if ($user_type === 'normal'): ?>
                    <div class="plan-card plan-featured">
                        <h3>Actualizar a Premium</h3>
                        <p class="plan-price">RD$500 / mes</p>
                        <ul class="plan-features">
                            <li>✔ Acceso ilimitado a todos los cursos</li>
                            <li>✔ Certificados gratuitos</li>
                            <li>✔ Sin anuncios</li>
                            <li>✔ Contenido exclusivo</li>
                            <li>✔ Soporte prioritario</li>
                        </ul>
                        <form method="POST">
                            <input type="hidden" name="action" value="upgrade">
                            <button type="submit" class="btn plan-btn">Activar Premium</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="plan-card">
                        <div class="premium-status">
                            <h3>
                                <img src="assets/icons/diamond-premium.svg" alt="Premium" class="dropdown-icon">
                                Eres Usuario Premium
                            </h3>
                            <p>Disfruta de todos los beneficios exclusivos</p>
                        </div>
                        <form method="POST">
                            <input type="hidden" name="action" value="cancel">
                            <button type="submit" class="btn btn-ghost"
                                onclick="return confirm('¿Estás seguro de que quieres cancelar tu suscripción Premium?')">
                                Cancelar Premium
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <?php include "componentes/footer.php"; ?>
</body>

</html>