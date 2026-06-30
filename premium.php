<?php
session_start();
$usuario = $_SESSION['usuario'] ?? null;
?>

<!DOCTYPE html>
<html>

<head>
<<<<<<< HEAD
    <link rel="stylesheet" href="/css/estilos.css">
=======
    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="responsive.css">
>>>>>>> 6ca2f45cad4ae75a0633f7c5434e9483eadd36aa
</head>

<body>

    <?php include "componentes/header.php"; ?>

    <div style="padding:40px; text-align:center;">
        <h2>Hazte Premium</h2>

        <?php if ($usuario && $usuario['tipo_usuario'] == "premium"): ?>

            <p style="color:green; font-size:20px;">Ya eres usuario Premium ✔</p>

        <?php else: ?>

            <div class="form-container">
                <h3>Plan Premium</h3>
                <p>Acceso ilimitado a cursos certificados</p>
                <p>RD$ 500 / mes</p>

                <a class="btn" href="proceso-suscripcion.php">Suscribirme</a>
            </div>

        <?php endif; ?>

    </div>

</body>

</html>