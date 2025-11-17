<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
}
$usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>

    <?php include "componentes/header.php"; ?>

    <div class="form-container">
        <h2>Mi Perfil</h2>

        <p><strong>Nombre:</strong> <?= $usuario['nombre'] ?></p>
        <p><strong>Correo:</strong> <?= $usuario['correo'] ?></p>
        <p><strong>Suscripción:</strong> <?= $usuario['tipo_usuario'] ?></p>

        <a class="btn" href="premium.php">Obtener Premium</a>
    </div>

</body>

</html>