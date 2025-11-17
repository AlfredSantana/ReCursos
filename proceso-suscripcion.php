<?php
session_start();
include "conexion.php";

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="estilos.css">
</head>

<body>

    <?php include "componentes/header.php"; ?>

    <div style="padding:60px; text-align:center;">
        <h2>Procesando tu suscripción...</h2>
        <p>Por favor espera unos segundos.</p>

        <img src="https://i.gifer.com/YCZH.gif" width="120">
    </div>

    <script>
        // Simulación del proceso de pago
        setTimeout(() => {
            window.location.href = "suscripcion-confirmada.php";
        }, 3000);
    </script>

</body>

</html>