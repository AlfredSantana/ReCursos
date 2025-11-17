<?php
session_start();
include "conexion.php";

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['usuario']['id'];

// Actualizar usuario a "premium"
mysqli_query($conexion, "UPDATE usuarios SET tipo_usuario='premium' WHERE id=$id");

// Actualizar sesión
$_SESSION['usuario']['tipo_usuario'] = "premium";
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>

    <?php include "componentes/header.php"; ?>

    <div style="text-align:center; padding:80px;">
        <h2>🎉 ¡Suscripción completada!</h2>
        <p>Ya eres usuario <strong>Premium</strong>.</p>

        <a class="btn" style="width:200px;" href="perfil.php">Ir a mi Perfil</a>
    </div>

</body>

</html>